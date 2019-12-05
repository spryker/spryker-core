<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Queue\Consumer;

use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Shared\Event\EventConfig as SharedEventConfig;
use Spryker\Zed\Event\Business\Exception\MessageTypeNotFoundException;
use Spryker\Zed\Event\Business\Logger\EventLoggerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;
use Spryker\Zed\Event\EventConfig;
use Throwable;

class EventQueueConsumer implements EventQueueConsumerInterface
{
    public const EVENT_TRANSFERS = 'eventTransfers';
    public const EVENT_MESSAGES = 'eventMessages';
    public const RETRY_KEY = 'retry';

    /**
     * @var \Spryker\Zed\Event\Business\Logger\EventLoggerInterface
     */
    protected $eventLogger;

    /**
     * @var \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Event\EventConfig
     */
    protected $eventConfig;

    /**
     * @var int|null
     */
    protected $maxRetry;

    /**
     * @param \Spryker\Zed\Event\Business\Logger\EventLoggerInterface $eventLogger
     * @param \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Event\EventConfig $eventConfig
     */
    public function __construct(
        EventLoggerInterface $eventLogger,
        EventToUtilEncodingInterface $utilEncodingService,
        EventConfig $eventConfig
    ) {

        $this->eventLogger = $eventLogger;
        $this->utilEncodingService = $utilEncodingService;
        $this->eventConfig = $eventConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers)
    {
        $bulkListener = [];
        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $eventQueueSentMessageBodyTransfer = $this->createEventQueueSentMessageBodyTransfer(
                $queueMessageTransfer->getQueueMessage()->getBody()
            );

            if (!$this->isMessageBodyValid($eventQueueSentMessageBodyTransfer)) {
                $this->markMessageAsFailed($queueMessageTransfer, 'Message body is not valid');
                continue;
            }

            try {
                $transfer = $this->mapEventTransfer($eventQueueSentMessageBodyTransfer);
                $bulkListener[$eventQueueSentMessageBodyTransfer->getListenerClassName()][$eventQueueSentMessageBodyTransfer->getEventName()][static::EVENT_TRANSFERS][] = $transfer;
                $bulkListener[$eventQueueSentMessageBodyTransfer->getListenerClassName()][$eventQueueSentMessageBodyTransfer->getEventName()][static::EVENT_MESSAGES][] = $queueMessageTransfer;

                $listener = $this->createEventListener($eventQueueSentMessageBodyTransfer->getListenerClassName());
                if ($listener instanceof EventHandlerInterface) {
                    $listener->handle($transfer, $eventQueueSentMessageBodyTransfer->getEventName());
                }

                $this->logConsumerAction(
                    sprintf(
                        '"%s" listener "%s" was successfully handled.',
                        $eventQueueSentMessageBodyTransfer->getEventName(),
                        $eventQueueSentMessageBodyTransfer->getListenerClassName()
                    )
                );

                $queueMessageTransfer->setAcknowledge(true);
            } catch (Throwable $exception) {
                $errorMessage = $this->createErrorMessage(
                    $eventQueueSentMessageBodyTransfer->getEventName(),
                    $eventQueueSentMessageBodyTransfer->getListenerClassName(),
                    $exception
                );
                $this->logConsumerAction($errorMessage, $exception);
                $this->handleFailedMessage($queueMessageTransfer, $errorMessage);
            }
        }

        foreach ($bulkListener as $listenerClassName => $eventItems) {
            $this->handleBulk($eventItems, $listenerClassName);
        }

        return $queueMessageTransfers;
    }

    /**
     * @param array $eventItems
     * @param string $listenerClassName
     *
     * @return void
     */
    protected function handleBulk(array $eventItems, $listenerClassName)
    {
        $listener = $this->createEventListener($listenerClassName);
        if (!($listener instanceof EventBulkHandlerInterface)) {
            return;
        }

        foreach ($eventItems as $eventName => $eventItem) {
            try {
                $listener->handleBulk($eventItem[static::EVENT_TRANSFERS], $eventName);
            } catch (Throwable $throwable) {
                $this->handleBulkItemsIndividually($eventItem, $eventName, $listener, $listenerClassName);
            }
        }
    }

    /**
     * @param array $eventItem
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface $listener
     * @param string $listenerClassName
     *
     * @return void
     */
    protected function handleBulkItemsIndividually(array $eventItem, string $eventName, EventBulkHandlerInterface $listener, string $listenerClassName): void
    {
        foreach ($eventItem[static::EVENT_TRANSFERS] as $key => $eventItemTransfer) {
            try {
                $listener->handleBulk([$eventItemTransfer], $eventName);
            } catch (Throwable $throwable) {
                $this->handleFailedEventItem($eventItem[static::EVENT_MESSAGES][$key], $eventName, $listenerClassName, $throwable);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $eventName
     * @param string $listenerClassName
     * @param \Throwable $throwable
     *
     * @return void
     */
    protected function handleFailedEventItem(
        QueueReceiveMessageTransfer $queueMessageTransfer,
        string $eventName,
        string $listenerClassName,
        Throwable $throwable
    ): void {
        $errorMessage = $this->createErrorMessage($eventName, $listenerClassName, $throwable);

        $this->logConsumerAction($errorMessage, $throwable);
        if (!$this->eventConfig->isLoggerActivated()) {
            $errorMessage = 'Please enable the event logger in the config_* files to see the error message: `$config[EventConstants::LOGGER_ACTIVE] = true;`';
        }

        $this->handleFailedMessage($queueMessageTransfer, $errorMessage);
    }

    /**
     * @param string $eventName
     * @param string $listenerClassName
     * @param \Throwable $exception
     *
     * @return string
     */
    protected function createErrorMessage(string $eventName, string $listenerClassName, Throwable $exception): string
    {
        $errorMessage = sprintf(
            'Failed to handle "%s" for listener "%s". Exception: "%s", "%s".',
            $eventName,
            $listenerClassName,
            $exception->getMessage(),
            $exception->getTraceAsString()
        );

        return $errorMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function handleFailedMessage(QueueReceiveMessageTransfer $queueMessageTransfer, string $errorMessage): void
    {
        $this->retryMessage($queueMessageTransfer, $errorMessage);
        $this->markMessageAsFailed($queueMessageTransfer, $errorMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $retryMessage
     *
     * @return void
     */
    protected function retryMessage(QueueReceiveMessageTransfer $queueMessageTransfer, string $retryMessage): void
    {
        if ($queueMessageTransfer->getRoutingKey()) {
            return;
        }

        $queueMessageBody = $this->utilEncodingService->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);
        $queueMessageBody = $this->updateMessageRetryKey($queueMessageBody);

        if ($this->maxRetry === null) {
            $this->maxRetry = $this->eventConfig->getMaxRetryAmount();
        }

        if ($queueMessageBody[static::RETRY_KEY] < $this->maxRetry) {
            $queueMessageBody[static::RETRY_KEY]++;
            $queueMessageTransfer->getQueueMessage()->setBody($this->utilEncodingService->encodeJson($queueMessageBody));
            $this->markMessageAsRetry($queueMessageTransfer, $retryMessage);
        }
    }

    /**
     * @param array $messageBody
     *
     * @return array
     */
    protected function updateMessageRetryKey(array $messageBody): array
    {
        if (!isset($messageBody[static::RETRY_KEY])) {
            $messageBody[static::RETRY_KEY] = 0;
        }

        return $messageBody;
    }

    /**
     * @param \Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer $eventQueueSendMessageBodyTransfer
     *
     * @return bool
     */
    protected function isMessageBodyValid(EventQueueSendMessageBodyTransfer $eventQueueSendMessageBodyTransfer)
    {
        if (!$eventQueueSendMessageBodyTransfer->getListenerClassName()) {
            $this->logConsumerAction('Listener class name is not set.');

            return false;
        }

        if (!$eventQueueSendMessageBodyTransfer->getTransferClassName()) {
            $this->logConsumerAction('Transfer class name is not set.');

            return false;
        }

        if (!class_exists($eventQueueSendMessageBodyTransfer->getListenerClassName())) {
            $this->logConsumerAction(
                sprintf(
                    'Listener class "%s" not found.',
                    $eventQueueSendMessageBodyTransfer->getListenerClassName()
                )
            );

            return false;
        }

        if (!class_exists($eventQueueSendMessageBodyTransfer->getTransferClassName())) {
            $this->logConsumerAction(
                sprintf(
                    'Transfer class "%s" not found.',
                    $eventQueueSendMessageBodyTransfer->getTransferClassName()
                )
            );

            return false;
        }

        return true;
    }

    /**
     * @param string $message
     * @param \Throwable|null $throwable
     *
     * @return void
     */
    protected function logConsumerAction($message, ?Throwable $throwable = null)
    {
        $this->eventLogger->log('[async] ' . $message);

        if ($throwable !== null) {
            ErrorLogger::getInstance()->log($throwable);
        }
    }

    /**
     * @param string $transferClass
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createEventTransfer($transferClass)
    {
        return new $transferClass();
    }

    /**
     * @param string $listenerClass
     *
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface|\Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface
     */
    protected function createEventListener($listenerClass)
    {
        return new $listenerClass();
    }

    /**
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer
     */
    protected function createEventQueueSentMessageBodyTransfer($body)
    {
        $eventQueueSentMessageBodyTransfer = new EventQueueSendMessageBodyTransfer();
        $eventQueueSentMessageBodyTransfer->fromArray(
            $this->utilEncodingService->decodeJson($body, true),
            true
        );

        return $eventQueueSentMessageBodyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function markMessageAsFailed(QueueReceiveMessageTransfer $queueMessageTransfer, $errorMessage = '')
    {
        if ($queueMessageTransfer->getRoutingKey()) {
            return;
        }

        $this->setMessage($queueMessageTransfer, 'errorMessage', $errorMessage);
        $queueMessageTransfer->setAcknowledge(false);
        $queueMessageTransfer->setReject(true);
        $queueMessageTransfer->setHasError(true);
        $queueMessageTransfer->setRoutingKey(SharedEventConfig::EVENT_ROUTING_KEY_ERROR);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $retryMessage
     *
     * @return void
     */
    protected function markMessageAsRetry(QueueReceiveMessageTransfer $queueMessageTransfer, $retryMessage = '')
    {
        $message = sprintf('Retry on: %s', $retryMessage);
        $this->setMessage($queueMessageTransfer, 'retryMessage', $message);
        $queueMessageTransfer->setAcknowledge(true);
        $queueMessageTransfer->setRoutingKey(SharedEventConfig::EVENT_ROUTING_KEY_RETRY);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $messageType
     * @param string $message
     *
     * @throws \Spryker\Zed\Event\Business\Exception\MessageTypeNotFoundException
     *
     * @return void
     */
    protected function setMessage(QueueReceiveMessageTransfer $queueMessageTransfer, string $messageType, string $message = '')
    {
        if (!$messageType) {
            throw new MessageTypeNotFoundException('message type is not defined');
        }

        $queueMessageBody = $this->utilEncodingService->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);
        $queueMessageBody[$messageType] = $message;
        $queueMessageTransfer->getQueueMessage()->setBody($this->utilEncodingService->encodeJson($queueMessageBody));
    }

    /**
     * @param \Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer $eventQueueSentMessageBodyTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function mapEventTransfer(EventQueueSendMessageBodyTransfer $eventQueueSentMessageBodyTransfer)
    {
        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer */
        $transfer = $this->createEventTransfer($eventQueueSentMessageBodyTransfer->getTransferClassName());
        $transfer->fromArray($eventQueueSentMessageBodyTransfer->getTransferData(), true);

        return $transfer;
    }
}

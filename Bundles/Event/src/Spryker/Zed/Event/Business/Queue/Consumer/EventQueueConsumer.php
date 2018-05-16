<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Queue\Consumer;

use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\Event\Business\Logger\EventLoggerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;
use Throwable;

class EventQueueConsumer implements EventQueueConsumerInterface
{
    const EVENT_TRANSFERS = 'eventTransfers';
    const EVENT_MESSAGES = 'eventMessages';
    /**
     * @var \Spryker\Zed\Event\Business\Logger\EventLoggerInterface
     */
    protected $eventLogger;

    /**
     * @var \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Event\Business\Logger\EventLoggerInterface $eventLogger
     * @param \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        EventLoggerInterface $eventLogger,
        EventToUtilEncodingInterface $utilEncodingService
    ) {

        $this->eventLogger = $eventLogger;
        $this->utilEncodingService = $utilEncodingService;
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
                $eventTransfer = $this->mapEventTransfer($eventQueueSentMessageBodyTransfer);
                $bulkListener[$eventQueueSentMessageBodyTransfer->getListenerClassName()][$eventQueueSentMessageBodyTransfer->getEventName()][static::EVENT_TRANSFERS][] = $eventTransfer;
                $bulkListener[$eventQueueSentMessageBodyTransfer->getListenerClassName()][$eventQueueSentMessageBodyTransfer->getEventName()][static::EVENT_MESSAGES][] = $queueMessageTransfer;

                $listener = $this->createEventListener($eventQueueSentMessageBodyTransfer->getListenerClassName());
                if ($listener instanceof EventHandlerInterface) {
                    $listener->handle($eventTransfer, $eventQueueSentMessageBodyTransfer->getEventName());
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
                $errorMessage = sprintf(
                    'Failed to handle "%s" for listener "%s". Exception: "%s", "%s".',
                    $eventQueueSentMessageBodyTransfer->getEventName(),
                    $eventQueueSentMessageBodyTransfer->getListenerClassName(),
                    $exception->getMessage(),
                    $exception->getTraceAsString()
                );
                $this->logConsumerAction($errorMessage, $exception);
                $this->markMessageAsFailed($queueMessageTransfer, $errorMessage);
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
                $errorMessage = sprintf(
                    'Failed to handle "%s" for listener "%s". Exception: "%s", "%s".',
                    $eventName,
                    $listenerClassName,
                    $throwable->getMessage(),
                    $throwable->getTraceAsString()
                );
                $this->logConsumerAction($errorMessage, $throwable);
                foreach ($eventItem[static::EVENT_MESSAGES] as $queueMessageTransfer) {
                    $this->markMessageAsFailed($queueMessageTransfer, $errorMessage);
                }
            }
        }
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
    protected function logConsumerAction($message, Throwable $throwable = null)
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
        return new $transferClass;
    }

    /**
     * @param string $listenerClass
     *
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface|\Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface
     */
    protected function createEventListener($listenerClass)
    {
        return new $listenerClass;
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
        $this->setMessageError($queueMessageTransfer, $errorMessage);
        $queueMessageTransfer->setAcknowledge(false);
        $queueMessageTransfer->setReject(true);
        $queueMessageTransfer->setHasError(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function setMessageError(QueueReceiveMessageTransfer $queueMessageTransfer, $errorMessage = '')
    {
        $queueMessageBody = $this->utilEncodingService->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);
        $queueMessageBody['errorMessage'] = $errorMessage;
        $queueMessageTransfer->getQueueMessage()->setBody($this->utilEncodingService->encodeJson($queueMessageBody));
    }

    /**
     * @param \Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer $eventQueueSentMessageBodyTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function mapEventTransfer(EventQueueSendMessageBodyTransfer $eventQueueSentMessageBodyTransfer)
    {
        $eventTransfer = $this->createEventTransfer($eventQueueSentMessageBodyTransfer->getTransferClassName());
        $eventTransfer->fromArray($eventQueueSentMessageBodyTransfer->getTransferData(), true);

        return $eventTransfer;
    }
}

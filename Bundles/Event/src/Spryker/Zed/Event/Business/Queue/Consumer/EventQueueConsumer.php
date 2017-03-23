<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Queue\Consumer;

use Exception;
use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Zed\Event\Business\Logger\EventLoggerInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;

class EventQueueConsumer implements EventQueueConsumerInterface
{

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
        foreach ($queueMessageTransfers as $queueMessageTransfer) {

            $eventQueueSentMessageBodyTransfer = $this->createEventQueueSentMessageBodyTransfer(
                $queueMessageTransfer->getQueueMessage()->getBody()
            );

            if (!$this->isMessageBodyValid($eventQueueSentMessageBodyTransfer)) {
                $this->markMessageAsFailed($queueMessageTransfer);
                continue;
            }

            try {
                $eventTransfer = $this->mapEventTransfer($eventQueueSentMessageBodyTransfer);

                $listener = $this->createEventListener($eventQueueSentMessageBodyTransfer->getListenerClassName());
                $listener->handle($eventTransfer);

                $this->logConsumerAction(
                    sprintf(
                        '"%s" listener "%s" was successfully handled.',
                        $eventQueueSentMessageBodyTransfer->getEventName(),
                        $eventQueueSentMessageBodyTransfer->getListenerClassName()
                    )
                );

                $queueMessageTransfer->setAcknowledge(true);

            } catch (Exception $exception) {
                $this->logConsumerAction(
                    sprintf(
                        'Failed to handle "%s" for listener "%s". Exception: "%s", "%s".',
                        $eventQueueSentMessageBodyTransfer->getEventName(),
                        $eventQueueSentMessageBodyTransfer->getListenerClassName(),
                        $exception->getMessage(),
                        $exception->getTraceAsString()
                    )
                );
                $this->markMessageAsFailed($queueMessageTransfer);
            }
        }

        return $queueMessageTransfers;
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
     *
     * @return void
     */
    protected function logConsumerAction($message)
    {
        $this->eventLogger->log('[async] ' . $message);
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
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface
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
     *
     * @return void
     */
    protected function markMessageAsFailed(QueueReceiveMessageTransfer $queueMessageTransfer)
    {
        //should we drop or requeue messages?
        $queueMessageTransfer->setReject(true);
        $queueMessageTransfer->setHasError(true);
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

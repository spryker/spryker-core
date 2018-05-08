<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Queue\Producer;

use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Client\EventToQueueInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;

class EventQueueProducer implements EventQueueProducerInterface
{
    /**
     * @var \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface $queueClient
     * @param \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        EventToQueueInterface $queueClient,
        EventToUtilEncodingInterface $utilEncodingService
    ) {
        $this->queueClient = $queueClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param string $listener
     *
     * @return void
     */
    public function enqueueListener($eventName, TransferInterface $eventTransfer, $listener)
    {
        $messageTransfer = new QueueSendMessageTransfer();
        $messageTransfer->setBody(
            $this->utilEncodingService->encodeJson(
                $this->mapQueueMessageBody($eventTransfer, $listener, $eventName)
            )
        );

        $this->queueClient->sendMessage(EventConstants::EVENT_QUEUE, $messageTransfer);
    }

    /**
     * //TODO extract the methods and reuse it
     *
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $listener
     *
     * @return void
     */
    public function enqueueListenerBulk($eventName, array $eventTransfers, $listener)
    {
        $messageTransfers = [];
        foreach ($eventTransfers as $eventTransfer) {
            $messageTransfer = new QueueSendMessageTransfer();
            $messageTransfer->setBody(
                $this->utilEncodingService->encodeJson(
                    $this->mapQueueMessageBody($eventTransfer, $listener, $eventName)
                )
            );
            $messageTransfers[] = $messageTransfer;
        }


        $this->queueClient->sendMessages(EventConstants::EVENT_QUEUE, $messageTransfers);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param string $listenerClassName
     * @param string $eventName
     *
     * @return array
     */
    protected function mapQueueMessageBody(TransferInterface $eventTransfer, $listenerClassName, $eventName)
    {
        return [
            EventQueueSendMessageBodyTransfer::LISTENER_CLASS_NAME => $listenerClassName,
            EventQueueSendMessageBodyTransfer::TRANSFER_CLASS_NAME => get_class($eventTransfer),
            EventQueueSendMessageBodyTransfer::TRANSFER_DATA => $eventTransfer->toArray(),
            EventQueueSendMessageBodyTransfer::EVENT_NAME => $eventName,
        ];
    }
}

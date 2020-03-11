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
use Spryker\Zed\Event\EventConfig;

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
     * @var \Spryker\Zed\Event\EventConfig
     */
    protected $eventConfig;

    /**
     * @param \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface $queueClient
     * @param \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Event\EventConfig $eventConfig
     */
    public function __construct(
        EventToQueueInterface $queueClient,
        EventToUtilEncodingInterface $utilEncodingService,
        EventConfig $eventConfig
    ) {
        $this->queueClient = $queueClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->eventConfig = $eventConfig;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     * @param string $listener
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return void
     */
    public function enqueueListenerBulk($eventName, array $transfers, $listener, $queuePoolName = null, $eventQueueName = null): void
    {
        $transfers = array_chunk($transfers, $this->eventConfig->getEnqueueEventMessageChunkSize());

        foreach ($transfers as $transfersChunk) {
            $this->enqueueListenerBulkChunk($eventName, $transfersChunk, $listener, $queuePoolName, $eventQueueName);
        }
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $listener
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return void
     */
    public function enqueueListener($eventName, TransferInterface $transfer, $listener, $queuePoolName = null, $eventQueueName = null)
    {
        $messageTransfer = $this->createQueueSendMessageTransfer($eventName, $transfer, $listener, $queuePoolName);

        $queueName = $eventQueueName ?? EventConstants::EVENT_QUEUE;

        $this->queueClient->sendMessage($queueName, $messageTransfer);
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     * @param string $listener
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return void
     */
    protected function enqueueListenerBulkChunk(string $eventName, array $transfers, string $listener, ?string $queuePoolName = null, ?string $eventQueueName = null): void
    {
        $queueSendMessageTransfers = [];
        foreach ($transfers as $transfer) {
            $queueSendMessageTransfers[] = $this->createQueueSendMessageTransfer($eventName, $transfer, $listener, $queuePoolName);
        }

        $queueName = $eventQueueName ?? EventConstants::EVENT_QUEUE;

        $this->queueClient->sendMessages($queueName, $queueSendMessageTransfers);
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $listener
     * @param string|null $queuePoolName
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    protected function createQueueSendMessageTransfer($eventName, TransferInterface $transfer, $listener, $queuePoolName = null)
    {
        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueSendMessageTransfer->setQueuePoolName($queuePoolName);
        $queueSendMessageTransfer->setBody(
            $this->utilEncodingService->encodeJson(
                $this->mapQueueMessageBody($transfer, $listener, $eventName)
            )
        );

        return $queueSendMessageTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $listenerClassName
     * @param string $eventName
     *
     * @return array
     */
    protected function mapQueueMessageBody(TransferInterface $transfer, $listenerClassName, $eventName)
    {
        return [
            EventQueueSendMessageBodyTransfer::LISTENER_CLASS_NAME => $listenerClassName,
            EventQueueSendMessageBodyTransfer::TRANSFER_CLASS_NAME => get_class($transfer),
            EventQueueSendMessageBodyTransfer::TRANSFER_DATA => $transfer->toArray(),
            EventQueueSendMessageBodyTransfer::EVENT_NAME => $eventName,
        ];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Queue\Forwarder;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Zed\Event\Dependency\Client\EventToQueueInterface;

class MessageForwarder implements MessageForwarderInterface
{
    /**
     * @var \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface
     */
    protected $queueClient;

    /**
     * @param \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface $queueClient
     */
    public function __construct(EventToQueueInterface $queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function forwardMessages(array $queueMessageTransfers): array
    {
        $responses = [];

        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $queueMessageTransfer->setAcknowledge(true);
            $this->sendMessage($queueMessageTransfer);

            $responses[] = $queueMessageTransfer;
        }

        return $responses;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    protected function sendMessage(QueueReceiveMessageTransfer $queueMessageTransfer): void
    {
        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueSendMessageTransfer->fromArray($queueMessageTransfer->getQueueMessage()->toArray(), true);
        $this->queueClient->sendMessage($queueMessageTransfer->getQueueName(), $queueSendMessageTransfer);
    }
}

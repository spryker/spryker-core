<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency\Client;

use Generated\Shared\Transfer\QueueSendMessageTransfer;

class EventToQueueBridge implements EventToQueueInterface
{
    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct($queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer)
    {
        $this->queueClient->sendMessage($queueName, $queueSendMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param array<\Generated\Shared\Transfer\QueueSendMessageTransfer> $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers)
    {
        $this->queueClient->sendMessages($queueName, $queueSendMessageTransfers);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Communication\Plugin\Queue;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\Queue\QueueClient;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface;

/**
 * @method \Spryker\Zed\Event\Business\EventFacadeInterface getFacade()
 * @method \Spryker\Zed\Event\EventConfig getConfig()
 */
class EventRetryQueueMessageProcessorPlugin extends AbstractPlugin implements QueueMessageProcessorPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers)
    {
        $responses = [];
        //TODO refactor this
        $queueClient = new QueueClient();
        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $responses[] = $queueMessageTransfer;
            $queueMessageTransfer->setAcknowledge(true);
            $queueSendMessageTransfer = new QueueSendMessageTransfer();
            $queueSendMessageTransfer->fromArray($queueMessageTransfer->getQueueMessage()->toArray());
            $queueClient->sendMessage($queueMessageTransfer->getQueueName(), $queueSendMessageTransfer);
        }

        return $responses;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getChunkSize()
    {
        return $this->getConfig()->getEventQueueMessageChunkSize();
    }
}

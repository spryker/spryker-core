<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Communication\Plugin\Queue;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface;

/**
 * @method \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface getFacade()
 * @method \Spryker\Zed\Synchronization\Communication\SynchronizationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Synchronization\SynchronizationConfig getConfig()
 */
class SynchronizationStorageQueueMessageProcessorPlugin extends AbstractPlugin implements QueueMessageProcessorPluginInterface
{
    const WRITE_TYPE = 'write';
    const DELETE_TYPE = 'delete';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers)
    {
        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $message = $this->getFactory()->getUtilEncodingService()->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);

            if (isset($message[static::WRITE_TYPE])) {
                $this->getFacade()->storageWrite($message[static::WRITE_TYPE], $queueMessageTransfer->getQueueName());
            }

            if (isset($message[static::DELETE_TYPE])) {
                $this->getFacade()->storageDelete($message[static::DELETE_TYPE], $queueMessageTransfer->getQueueName());
            }

            $queueMessageTransfer->setAcknowledge(true);
        }

        return $queueMessageTransfers;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getChunkSize()
    {
        return $this->getConfig()->getSyncStorageQueueMessageChunkSize();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Dependency\Plugin;

interface QueueMessageProcessorPluginInterface
{
    /**
     * Specification:
     * - This plugin interface is used for message processing for the queues,
     *   by implementing this and adding to QueueDependencyProvider::getProcessorMessagePlugins()
     *   for specific queue, receives messages will pass to this method for processing
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers);

    /**
     * Specification:
     * - Returns the number of messages which need to fetch
     *   from queue
     *
     * @api
     *
     * @return int
     */
    public function getChunkSize();
}

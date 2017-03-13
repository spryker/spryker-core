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
     * - This plugin interface is using for message processing for the queues
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers);

    /**
     * @api
     *
     * @return int
     */
    public function getChunkSize();

}

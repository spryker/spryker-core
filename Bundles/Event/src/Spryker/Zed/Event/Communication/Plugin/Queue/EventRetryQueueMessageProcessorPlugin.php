<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Communication\Plugin\Queue;

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
        return $this->getFacade()->forwardMessages($queueMessageTransfers);
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

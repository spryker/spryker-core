<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;

interface QueueMessageCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param string|null $store
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function createQueueMessage(
        SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer,
        SynchronizationDataPluginInterface $plugin,
        $store = null
    ): QueueSendMessageTransfer;
}

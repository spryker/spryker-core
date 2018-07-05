<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;

interface QueueMessageCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer
     * @param string|null $store
     * @param string|null $queuePoolName
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function createQueueMessage(SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer, $store = null, $queuePoolName = null);
}

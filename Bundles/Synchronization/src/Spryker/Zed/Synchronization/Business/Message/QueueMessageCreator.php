<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Spryker\Zed\Synchronization\Business\Exception\SynchronizationQueuePoolNotFoundException;

class QueueMessageCreator implements QueueMessageCreatorInterface
{
    protected const WRITE = 'write';

    /**
     * @param \Generated\Shared\Transfer\SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer
     * @param string|null $store
     * @param string|null $queuePoolName
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function createQueueMessage(SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer, $store = null, $queuePoolName = null)
    {
        $message = [];
        $message[static::WRITE] = $synchronizationQueueMessageTransfer->toArray();

        return $this->createQueueSendMessageTransfer($message, $store, $queuePoolName);
    }

    /**
     * @param array $message
     * @param string|null $store
     * @param string|null $queuePoolName
     *
     * @throws \Spryker\Zed\Synchronization\Business\Exception\SynchronizationQueuePoolNotFoundException
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    protected function createQueueSendMessageTransfer(array $message, $store = null, $queuePoolName = null)
    {
        $queueSendTransfer = new QueueSendMessageTransfer();
        $queueSendTransfer->setBody(json_encode($message));

        if ($store) {
            $queueSendTransfer->setStoreName($store);

            return $queueSendTransfer;
        }

        if (!$queuePoolName) {
            throw new SynchronizationQueuePoolNotFoundException('You must either have store column or `SynchronizationQueuePoolName` in your schema.xml file');
        }
        $queueSendTransfer->setQueuePoolName($queuePoolName);

        return $queueSendTransfer;
    }
}

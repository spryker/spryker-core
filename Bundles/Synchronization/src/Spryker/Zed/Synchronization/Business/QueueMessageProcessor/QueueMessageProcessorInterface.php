<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\QueueMessageProcessor;

use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;

interface QueueMessageProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers): array;

    /**
     * @param \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface $synchronization
     *
     * @return void
     */
    public function setSynchronization(SynchronizationInterface $synchronization): void;
}

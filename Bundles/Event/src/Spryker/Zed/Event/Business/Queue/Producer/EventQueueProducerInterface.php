<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business\Queue\Producer;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface EventQueueProducerInterface
{
    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $listener
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return void
     */
    public function enqueueListener($eventName, TransferInterface $transfer, $listener, $queuePoolName = null, $eventQueueName = null);

    /**
     * @param string $eventName
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     * @param string $listener
     * @param string|null $queuePoolName
     * @param string|null $eventQueueName
     *
     * @return void
     */
    public function enqueueListenerBulk($eventName, array $transfers, $listener, $queuePoolName = null, $eventQueueName = null): void;
}

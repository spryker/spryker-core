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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param string $listener
     * @param string|null $queuePoolName
     *
     * @return void
     */
    public function enqueueListener($eventName, TransferInterface $eventTransfer, $listener, $queuePoolName = null);
}

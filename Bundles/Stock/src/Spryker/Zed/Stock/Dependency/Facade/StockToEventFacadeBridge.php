<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Dependency\Facade;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;

class StockToEventFacadeBridge implements StockToEventFacadeInterface
{
    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    protected EventFacadeInterface $eventFacade;

    /**
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade
     */
    public function __construct($eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function trigger(string $eventName, TransferInterface $eventTransfer): void
    {
        $this->eventFacade->trigger($eventName, $eventTransfer);
    }
}

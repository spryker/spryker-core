<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency\Facade;

class ProductLabelToEventBridge implements ProductLabelToEventInterface
{
    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Event\Business\EventFacadeInterface $eventFacade
     */
    public function __construct($eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param string $eventName
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $transfers
     *
     * @return void
     */
    public function triggerBulk(string $eventName, array $transfers): void
    {
        if (method_exists($this->eventFacade, 'triggerBulk')) {
            $this->eventFacade->triggerBulk($eventName, $transfers);

            return;
        }

        foreach ($transfers as $transfer) {
            $this->eventFacade->trigger($eventName, $transfer);
        }
    }
}

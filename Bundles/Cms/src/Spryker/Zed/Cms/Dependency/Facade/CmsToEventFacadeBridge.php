<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

class CmsToEventFacadeBridge implements CmsToEventFacadeInterface
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
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function triggerBulk(string $eventName, array $eventTransfers): void
    {
        $this->eventFacade->triggerBulk($eventName, $eventTransfers);
    }
}

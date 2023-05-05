<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business\Deleter;

use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface;

class ServicePointSearchDeleter implements ServicePointSearchDeleterInterface
{
    /**
     * @var \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface
     */
    protected ServicePointSearchEntityManagerInterface $servicePointSearchEntityManager;

    /**
     * @var \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface
     */
    protected ServicePointSearchToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface $servicePointSearchEntityManager
     */
    public function __construct(
        ServicePointSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ServicePointSearchEntityManagerInterface $servicePointSearchEntityManager
    ) {
        $this->servicePointSearchEntityManager = $servicePointSearchEntityManager;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByServicePointEvents(array $eventTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->servicePointSearchEntityManager
            ->deleteServicePointSearchByServicePointIds(array_unique($servicePointIds));
    }
}

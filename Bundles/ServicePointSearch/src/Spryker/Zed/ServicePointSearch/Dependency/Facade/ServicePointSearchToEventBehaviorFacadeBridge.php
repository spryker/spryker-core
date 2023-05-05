<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Dependency\Facade;

class ServicePointSearchToEventBehaviorFacadeBridge implements ServicePointSearchToEventBehaviorFacadeInterface
{
    /**
     * @var \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct($eventBehaviorFacade)
    {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return list<int>
     */
    public function getEventTransferIds(array $eventTransfers): array
    {
        return $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return list<int>
     */
    public function getEventTransferForeignKeys(array $eventTransfers, string $foreignKeyColumnName): array
    {
        return $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, $foreignKeyColumnName);
    }
}

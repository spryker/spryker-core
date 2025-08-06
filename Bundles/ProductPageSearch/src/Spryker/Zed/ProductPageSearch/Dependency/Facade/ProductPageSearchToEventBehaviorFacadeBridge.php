<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Generated\Shared\Transfer\HydrateEventsResponseTransfer;

class ProductPageSearchToEventBehaviorFacadeBridge implements ProductPageSearchToEventBehaviorFacadeInterface
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
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers)
    {
        return $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName)
    {
        return $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, $foreignKeyColumnName);
    }

    /**
     * @param \Generated\Shared\Transfer\HydrateEventsRequestTransfer $hydrateEventsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HydrateEventsResponseTransfer
     */
    public function hydrateEventDataTransfer(HydrateEventsRequestTransfer $hydrateEventsRequestTransfer): HydrateEventsResponseTransfer
    {
        return $this->eventBehaviorFacade->hydrateEventDataTransfer($hydrateEventsRequestTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param array<string> $columns
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns)
    {
        return $this->eventBehaviorFacade->getEventTransfersByModifiedColumns($eventTransfers, $columns);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getGroupedEventTransferForeignKeysByForeignKey(array $eventTransfers, string $foreignKeyColumnName)
    {
        return $this->eventBehaviorFacade->getGroupedEventTransferForeignKeysByForeignKey($eventTransfers, $foreignKeyColumnName);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $columnName
     *
     * @return array
     */
    public function getEventTransfersAdditionalValues(array $eventTransfers, string $columnName): array
    {
        return $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, $columnName);
    }
}

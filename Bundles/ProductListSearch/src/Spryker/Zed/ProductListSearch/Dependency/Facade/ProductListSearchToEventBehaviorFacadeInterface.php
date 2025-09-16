<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Dependency\Facade;

use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Generated\Shared\Transfer\HydrateEventsResponseTransfer;

interface ProductListSearchToEventBehaviorFacadeInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array<int>
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName): array;

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return array<int>
     */
    public function getEventTransferIds(array $eventTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\HydrateEventsRequestTransfer $hydrateEventsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HydrateEventsResponseTransfer
     */
    public function hydrateEventDataTransfer(HydrateEventsRequestTransfer $hydrateEventsRequestTransfer): HydrateEventsResponseTransfer;
}

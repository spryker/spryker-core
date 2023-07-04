<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade;

interface ProductOfferShipmentTypeStorageToEventBehaviorFacadeInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return list<int>
     */
    public function getEventTransferIds(array $eventTransfers): array;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return list<int>
     */
    public function getEventTransferForeignKeys(array $eventTransfers, string $foreignKeyColumnName): array;
}

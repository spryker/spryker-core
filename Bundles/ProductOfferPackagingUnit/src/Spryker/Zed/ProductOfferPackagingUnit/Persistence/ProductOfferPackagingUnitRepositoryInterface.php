<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductOfferPackagingUnitRepositoryInterface
{
    /**
     * @param string $productOfferReference
     * @param \ArrayObject|\Generated\Shared\Transfer\OmsStateTransfer[] $omsStateTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(
        string $productOfferReference,
        ArrayObject $omsStateTransfers,
        ?StoreTransfer $storeTransfer = null
    ): array;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;

interface ProductOfferAvailabilityStorageRepositoryInterface
{
    /**
     * @param string[] $productOfferStockIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    public function getProductOfferAvailabilityRequestsByProductOfferStockIds(array $productOfferStockIds): array;

    /**
     * @param array $productOfferStockIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    public function getProductOfferAvailabilityRequestsByProductOfferIds(array $productOfferStockIds): array;

    /**
     * @param array $omsProductOfferReservationIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer[]
     */
    public function getProductOfferAvailabilityRequestsByOmsProductOfferReservationIds(array $omsProductOfferReservationIds): array;

    /**
     * @param string $offerReference
     * @param string $storeName
     *
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage|null
     */
    public function findProductOfferAvailabilityStorageByProductOfferReferenceAndStoreName(
        string $offerReference,
        string $storeName
    ): ?SpyProductOfferAvailabilityStorage;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SpyProductOfferAvailabilityStorageEntityTransfer[]
     */
    public function getFilteredProductOfferAvailabilityStorageEntityTransfers(FilterTransfer $filterTransfer, array $ids): array;
}

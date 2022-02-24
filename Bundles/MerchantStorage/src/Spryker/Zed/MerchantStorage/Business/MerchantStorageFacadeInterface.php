<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

interface MerchantStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes merchant data to storage based on merchant events.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes merchant data to storage based on merchant category events.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantCategoryEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Filters `ProductOfferCollection` transfer object by active merchant.
     * - Returns `ProductOfferCollection` transfer object excluded product offers with no active merchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function filterProductOfferStorages(ProductOfferCollectionTransfer $productOfferCollectionTransfer): ProductOfferCollectionTransfer;
}

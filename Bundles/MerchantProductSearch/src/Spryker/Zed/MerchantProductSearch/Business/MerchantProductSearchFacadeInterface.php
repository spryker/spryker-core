<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Business;

interface MerchantProductSearchFacadeInterface
{
    /**
     * Specification:
     *  - Gets merchant ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by merchant ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Calls product page search facade to refresh stored data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets abstract product merchant ids from eventTransfers.
     *  - Queries all product abstract with the given abstract product merchant ids.
     *  - Calls product page search facade to refresh stored data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Returns a list of ProductAbstractMerchantTransfers with data about merchant by product abstract ids.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array;
}

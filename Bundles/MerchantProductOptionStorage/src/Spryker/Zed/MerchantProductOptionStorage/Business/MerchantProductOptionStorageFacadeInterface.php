<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Business;

interface MerchantProductOptionStorageFacadeInterface
{
    /**
     * Specification:
     * - Retrieves all merchant product option group ids from $eventTransfers.
     * - Finds all product abstract ids that own merchant product options.
     * - Calls publish functionality from product option stroage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantProductOptionGroupEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Filters merchant product option group transfers by approval status.
     * - Excludes product options with not approved merchant groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    public function filterProductOptions(array $productOptionTransfers): array;
}

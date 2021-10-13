<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorageExtension\Dependency\Plugin;

/**
 * Filters out merchant prices before saving to storage.
 */
interface PriceProductMerchantRelationshipStorageFilterPluginInterface
{
    /**
     * Specification:
     * - Filters `PriceProductMerchantRelationshipStorageTransfer` out from array if it is non-actual or|and does not fit certain conditions.
     * - Returns array of filtered `PriceProductMerchantRelationshipStorageTransfer`.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function filter(array $priceProductMerchantRelationshipStorageTransfers): array;
}

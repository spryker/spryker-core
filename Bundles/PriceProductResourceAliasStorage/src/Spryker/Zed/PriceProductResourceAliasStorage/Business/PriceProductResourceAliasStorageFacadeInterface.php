<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Business;

interface PriceProductResourceAliasStorageFacadeInterface
{
    /**
     * Specification:
     *  - Fills/updates sku field in price product abstract storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function updatePriceProductAbstractStorageSkus(array $productAbstractIds): void;

    /**
     * Specification:
     *  - Fills/updates sku field in price product concrete storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByStoreIds(array $productConcreteIds): void;

    /**
     * Specification:
     *  - Fills/updates sku field in price product concrete storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param int[] $priceProductStoreIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByProductConcreteIds(array $priceProductStoreIds): void;
}

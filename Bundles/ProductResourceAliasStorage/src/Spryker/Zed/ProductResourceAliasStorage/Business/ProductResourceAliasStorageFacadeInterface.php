<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Business;

interface ProductResourceAliasStorageFacadeInterface
{
    /**
     * Specification:
     *  - Fills/updates sku field in product abstract storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function updateProductAbstractStorageSkus(array $productAbstractIds): void;

    /**
     * Specification:
     *  - Fills/updates sku field in product concrete storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return void
     */
    public function updateProductConcreteStorageSkus(array $productConcreteIds): void;
}

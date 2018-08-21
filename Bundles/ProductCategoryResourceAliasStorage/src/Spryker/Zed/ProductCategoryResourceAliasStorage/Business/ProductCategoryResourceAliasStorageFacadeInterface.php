<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Business;

interface ProductCategoryResourceAliasStorageFacadeInterface
{
    /**
     * Specification:
     *  - Fills/updates sku field in product abstract storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function updateProductAbstractCategoryStorageSkus(array $productAbstractIds): void;
}

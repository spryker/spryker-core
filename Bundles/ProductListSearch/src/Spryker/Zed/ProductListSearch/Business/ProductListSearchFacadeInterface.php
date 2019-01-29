<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Business;

use Generated\Shared\Transfer\ProductListMapTransfer;

interface ProductListSearchFacadeInterface
{
    /**
     * Specification:
     *  - Retrieve list of abstract product ids by concrete product ids.
     *
     * @api
     *
     * @param int[] $concreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array;

    /**
     * Specification:
     *  - Retrieve list of abstract product ids by category ids.
     *
     * @api
     *
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;

    /**
     *  Specification:
     *  - Maps ProductList data to ProductListMapTransfer.
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductListMapTransfer $productListMapTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListMapTransfer
     */
    public function mapProductDataToProductListMapTransfer(array $productData, ProductListMapTransfer $productListMapTransfer): ProductListMapTransfer;
}

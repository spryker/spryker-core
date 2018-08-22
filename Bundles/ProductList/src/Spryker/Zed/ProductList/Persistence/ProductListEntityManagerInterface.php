<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductListProductRelations(ProductListTransfer $productListTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductListCategoryRelations(ProductListTransfer $productListTransfer): void;

    /**
     * @param int $idProductList
     * @param int[] $categoryIds
     *
     * @return void
     */
    public function addCategoryRelations(int $idProductList, array $categoryIds): void;

    /**
     * @param int $idProductList
     * @param int[] $categoryIds
     *
     * @return void
     */
    public function removeCategoryRelations(int $idProductList, array $categoryIds): void;

    /**
     * @param int $idProductList
     * @param int[] $productIds
     *
     * @return void
     */
    public function addProductConcreteRelations(int $idProductList, array $productIds): void;

    /**
     * @param int $idProductList
     * @param int[] $productIds
     *
     * @return void
     */
    public function removeProductConcreteRelations(int $idProductList, array $productIds): void;
}

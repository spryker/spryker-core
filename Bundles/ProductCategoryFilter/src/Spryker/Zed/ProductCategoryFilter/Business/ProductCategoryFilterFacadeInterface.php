<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterFacadeInterface
{
    /**
     * Specification:
     * - Persist new product category filter entity into database.
     * - The returned transfer contains the ID of the created product category filter.
     * - Touches "product_category_filter" entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function createProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer);

    /**
     * Specification:
     * - Updates existing product category filter in database.
     * - Touches "product_category_filter" entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function updateProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer);

    /**
     * Specification:
     * - Finds existing product category filter in database.
     * - Returns the product category filter.
     *
     * @api
     *
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function findProductCategoryFilterByCategoryId($categoryId);

    /**
     * Specification:
     * - Returns an array of product category IDs that have specific filters attached to them
     *
     * @api
     *
     * @return array
     */
    public function getAllProductCategoriesWithFilters();

    /**
     * Specification:
     * - Removes existing product category filter from database.
     * - Touches "product_category_Filter" as deleted.
     *
     * @api
     *
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId);
}

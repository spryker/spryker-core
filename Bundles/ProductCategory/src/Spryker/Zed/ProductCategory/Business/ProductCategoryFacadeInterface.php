<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryFacadeInterface
{
    /**
     * Specification:
     * - Creates and persists new category mapping entries to database.
     * - If a product category mapping already exists, same logic will still apply.
     * - Touches affected category.
     * - Touches affected abstract products.
     *
     * @api
     *
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign);

    /**
     * Specification:
     * - Removes existing product category mapping entries from database.
     * - Touches affected category.
     * - Touches affected abstract products.
     *
     * @api
     *
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign);

    /**
     * Specification:
     * - Updates order of existing product category mapping entries in database.
     * - Touches affected category.
     * - Touches affected abstract products.
     *
     * @api
     *
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList);

    /**
     * Specification:
     * - Removes all existing product category mapping entries from database.
     * - Touches affected category.
     * - Touches affected abstract products.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function removeAllProductMappingsForCategory($idCategory);

    /**
     * Specification:
     * - Returns all abstract products that are assigned to the given category.
     * - The data of the returned products are localized based on the given locale transfer.
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAbstractProductsByIdCategory($idCategory, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Touches related abstract-products for the given category and all of its children
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateAllProductMappingsForUpdatedCategory(CategoryTransfer $categoryTransfer);
}

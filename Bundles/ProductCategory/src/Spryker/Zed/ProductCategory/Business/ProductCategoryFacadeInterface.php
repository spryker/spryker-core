<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;

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
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getAbstractProductsByIdCategory($idCategory, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Touches related abstract-products for the given category and all of its children
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface::triggerProductUpdateEventsForCategory()} instead.
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateAllProductMappingsForUpdatedCategory(CategoryTransfer $categoryTransfer);

    /**
     * Specification:
     * - Returns all categories that are assigned to the given abstract product.
     * - The data of the returned categories are localized based on the given locale transfer.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, LocaleTransfer $localeTransfer): CategoryCollectionTransfer;

    /**
     * Specification:
     *  - Returns all concrete product ids by provided category ids.
     *
     * @api
     *
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByCategoryIds(array $categoryIds): array;

    /**
     * Specification:
     * - Gets localized products abstract names by category.
     * - Requires `CategoryTransfer.idCategory` to be set.
     * - Expects `LocaleTransfer.idLocale` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    public function getLocalizedProductAbstractNamesByCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): array;

    /**
     * Specification:
     * - Retrieves product categories by criteria from Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function getProductCategoryCollection(ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer): ProductCategoryCollectionTransfer;

    /**
     * Specification:
     * - Expands product concrete transfers with product categories.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithProductCategories(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Requires `Category.categoryNode.idCategoryNode` to be set.
     * - Triggers product update events for products that are assigned to the given category and its child categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEventsForCategory(CategoryTransfer $categoryTransfer): void;
}

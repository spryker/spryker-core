<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface getRepository()
 */
class ProductCategoryFacade extends AbstractFacade implements ProductCategoryFacadeInterface
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
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->createProductCategoryMappings($idCategory, $productIdsToAssign);
    }

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
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->removeProductCategoryMappings($idCategory, $productIdsToUnAssign);
    }

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
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->updateProductMappingsOrder($idCategory, $productOrderList);
    }

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
    public function removeAllProductMappingsForCategory($idCategory)
    {
        $this
            ->getFactory()
            ->createProductCategoryManager()
            ->removeMappings($idCategory);
    }

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
    public function getAbstractProductsByIdCategory($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createProductCategoryManager()
            ->getAbstractProductTransferCollectionByCategory($idCategory, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateAllProductMappingsForUpdatedCategory(CategoryTransfer $categoryTransfer)
    {
        $this
            ->getFactory()
            ->createProductCategoryManager()
            ->updateProductMappingsForUpdatedCategory($categoryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getProductAbstractCategoriesByIdProductAbstract(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->getFactory()
            ->createProductCategoryReader()
            ->getProductAbstractCategoriesByIdProductAbstract(
                $productAbstractTransfer,
                $localeTransfer
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getProductConcreteCategoriesByIdProductConcrete(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->getFactory()
            ->createProductCategoryReader()
            ->getProductConcreteCategoriesByIdProductConcrete(
                $productConcreteTransfer,
                $localeTransfer
            );
    }
}

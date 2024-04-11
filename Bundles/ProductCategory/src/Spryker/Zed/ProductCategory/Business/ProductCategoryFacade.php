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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface getRepository()
 */
class ProductCategoryFacade extends AbstractFacade implements ProductCategoryFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getAbstractProductsByIdCategory($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createProductCategoryManager()
            ->getAbstractProductTransferCollectionByCategory($idCategory, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductCategory\Business\ProductCategoryFacade::triggerProductUpdateEventsForCategory()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoryTransferCollectionByIdProductAbstract($idProductAbstract, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByCategoryIds(array $categoryIds): array
    {
        return $this->getRepository()
            ->getProductConcreteIdsByCategoryIds($categoryIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    public function getLocalizedProductAbstractNamesByCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->getFactory()
            ->createProductCategoryReader()
            ->getLocalizedProductAbstractNamesByCategory($categoryTransfer, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function getProductCategoryCollection(ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer): ProductCategoryCollectionTransfer
    {
        return $this->getRepository()->getProductCategoryCollection($productCategoryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithProductCategories(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createProductConcreteExpander()
            ->expandProductConcreteWithProductCategories($productConcreteTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEventsForCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getFactory()
            ->createProductCategoryEventTrigger()
            ->triggerProductUpdateEventsForCategory($categoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function triggerProductAbstractUpdateEventsByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createProductCategoryEventTrigger()
            ->triggerProductAbstractUpdateEvents($eventEntityTransfers);
    }
}

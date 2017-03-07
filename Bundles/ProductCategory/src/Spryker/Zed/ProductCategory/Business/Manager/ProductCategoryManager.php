<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Manager;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;

class ProductCategoryManager implements ProductCategoryManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface $categoryFacade
     * @param \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToProductInterface $productFacade
     */
    public function __construct(
        ProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductCategoryToCategoryInterface $categoryFacade,
        ProductCategoryToProductInterface $productFacade
    ) {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->categoryFacade = $categoryFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAbstractProductTransferCollectionByCategory(
        $idCategory,
        LocaleTransfer $localeTransfer
    ) {
        $productCollection = $this->getProductsByCategory($idCategory, $localeTransfer);
        $productTransferCollection = [];

        foreach ($productCollection as $productEntity) {
            $abstractProductTransfer = (new ProductAbstractTransfer())->fromArray($productEntity->toArray(), true);

            $localizedAttributesData = json_decode($productEntity->getVirtualColumn('abstract_localized_attributes'), true);
            $localizedAttributesTransfer = new LocalizedAttributesTransfer();
            $localizedAttributesTransfer->setName($productEntity->getVirtualColumn('name'));
            $localizedAttributesTransfer->setLocale($localeTransfer);
            $localizedAttributesTransfer->setAttributes($localizedAttributesData);
            $abstractProductTransfer->addLocalizedAttributes($localizedAttributesTransfer);

            $productTransferCollection[] = $abstractProductTransfer;
        }

        return $productTransferCollection;
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]
     */
    public function getProductsByCategory($idCategory, LocaleTransfer $locale)
    {
        return $this->productCategoryQueryContainer
            ->queryProductsByCategoryId($idCategory, $locale)
            ->orderByFkProductAbstract()
            ->find();
    }

    /**
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function getProductCategoryMappingById($idCategory, $idProductAbstract)
    {
        return $this->productCategoryQueryContainer
            ->queryProductCategoryMappingByIds($idCategory, $idProductAbstract);
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign)
    {
        foreach ($productIdsToUnAssign as $idProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if ($mapping === null) {
                continue;
            }

            $mapping->delete();

            $this->touchProductAbstractActive($idProduct);
        }

        $this->touchCategoryActive($idCategory);
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign)
    {
        foreach ($productIdsToAssign as $idProduct) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOneOrCreate();

            if ($mapping === null) {
                continue;
            }

            $mapping->setFkCategory($idCategory);
            $mapping->setFkProductAbstract($idProduct);
            $mapping->save();

            $this->touchProductAbstractActive($idProduct);
        }

        $this->touchCategoryActive($idCategory);
    }

    /**
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList)
    {
        foreach ($productOrderList as $idProduct => $order) {
            $mapping = $this->getProductCategoryMappingById($idCategory, $idProduct)
                ->findOne();

            if ($mapping === null) {
                continue;
            }

            $mapping->setFkCategory($idCategory);
            $mapping->setFkProductAbstract($idProduct);
            $mapping->setProductOrder($order);
            $mapping->save();

            $this->touchProductAbstractActive($idProduct);
        }

        $this->touchCategoryActive($idCategory);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function removeMappings($idCategory)
    {
        $assignedProducts = $this->productCategoryQueryContainer
            ->queryProductCategoryMappingsByCategoryId($idCategory)
            ->find();

        $productIdsToUnAssign = [];
        foreach ($assignedProducts as $mapping) {
            $productIdsToUnAssign[] = $mapping->getFkProductAbstract();
        }
        $this->removeProductCategoryMappings($idCategory, $productIdsToUnAssign);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchProductAbstractActive($idProductAbstract)
    {
        $this->productFacade->touchProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function touchCategoryActive($idCategory)
    {
        $this->categoryFacade->touchCategoryActive($idCategory);
    }

}

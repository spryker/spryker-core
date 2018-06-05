<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternativeTable;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTableItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeTableTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerInterface;

class ProductAlternativeTableHydrator implements ProductAlternativeTableHydratorInterface
{
    protected const COL_NAME = 'name';
    protected const COL_SKU = 'sku';
    protected const COL_STATUS = 'status';
    protected const COL_CATEGORY = 'category';

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface $productCategoryQueryContainer
     */
    public function __construct(
        ProductAlternativeToProductQueryContainerInterface $productQueryContainer,
        ProductAlternativeToLocaleFacadeInterface $localeFacade,
        ProductAlternativeToProductCategoryQueryContainerInterface $productCategoryQueryContainer
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableTransfer
     */
    public function hydrateProductAlternativeTable(ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer): ProductAlternativeTableTransfer
    {
        $productAlternativeTableTransfer = new ProductAlternativeTableTransfer();

        /** @var \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer */
        foreach ($productAlternativeCollectionTransfer->getProductAlternatives() as $productAlternativeTransfer) {
            $productAlternativeTableTransfer->addProductAlternativeTableItem(
                $this->resolveProductTypeHydration($productAlternativeTransfer)
            );
        }

        return $productAlternativeTableTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @throws \Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer
     */
    protected function resolveProductTypeHydration(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTableItemTransfer
    {
        $productAlternativeTransfer->requireIdProduct();

        $idProductConcrete = $productAlternativeTransfer
            ->getIdProductConcreteAlternative();

        if ($idProductConcrete) {
            return $this->hydrateTableItemWithProductConcreteAlternativeData(
                $idProductConcrete,
                new ProductAlternativeTableItemTransfer()
            );
        }

        $idProductAbstract = $productAlternativeTransfer
            ->getIdProductAbstractAlternative();

        if ($idProductAbstract) {
            return $this->hydrateTableItemWithProductAbstractAlternativeData(
                $idProductAbstract,
                new ProductAlternativeTableItemTransfer()
            );
        }

        throw new ProductAlternativeIsNotDefinedException(
            'You must set an id of abstract or concrete product alternative.'
        );
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer
     */
    protected function hydrateTableItemWithProductAbstractAlternativeData(
        int $idProductAbstractAlternative,
        ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
    ): ProductAlternativeTableItemTransfer {
        $productAbstractAlternative = $this
            ->getProductAbstractData($idProductAbstractAlternative);

        if (!$productAbstractAlternative) {
            return $productAlternativeTableItemTransfer;
        }

        $productAlternativeTableItemTransfer = $this->hydrateTableItemWithProductAbstractCategoryData(
            $idProductAbstractAlternative,
            $productAlternativeTableItemTransfer
        );

        return $productAlternativeTableItemTransfer
            ->setName($productAbstractAlternative->getVirtualColumn(static::COL_NAME))
            ->setSku($productAbstractAlternative->getSku())
            ->setStatus(
                $this->getProductAbstractStatus($productAbstractAlternative)
            );
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer
     */
    protected function hydrateTableItemWithProductConcreteAlternativeData(
        int $idProductConcreteAlternative,
        ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
    ): ProductAlternativeTableItemTransfer {
        $productConcreteAlternative = $this
            ->getProductConcreteData($idProductConcreteAlternative);

        if (!$productConcreteAlternative) {
            return $productAlternativeTableItemTransfer;
        }

        $productAlternativeTableItemTransfer = $this->hydrateTableItemWithProductConcreteCategoryData(
            $idProductConcreteAlternative,
            $productAlternativeTableItemTransfer
        );

        return $productAlternativeTableItemTransfer
            ->setName($productConcreteAlternative->getVirtualColumn(static::COL_NAME))
            ->setSku($productConcreteAlternative->getSku())
            ->setStatus($productConcreteAlternative->getIsActive());
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer
     */
    protected function hydrateTableItemWithProductAbstractCategoryData(
        int $idProductAbstractAlternative,
        ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
    ): ProductAlternativeTableItemTransfer {
        $productAbstractCategories = $this
            ->getProductAbstractCategories($idProductAbstractAlternative);

        if (empty($productAbstractCategories)) {
            return $productAlternativeTableItemTransfer;
        }

        return $productAlternativeTableItemTransfer
            ->setCategories($productAbstractCategories);
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableItemTransfer
     */
    protected function hydrateTableItemWithProductConcreteCategoryData(
        int $idProductConcreteAlternative,
        ProductAlternativeTableItemTransfer $productAlternativeTableItemTransfer
    ): ProductAlternativeTableItemTransfer {
        $productConcreteCategories = $this
            ->getProductConcreteCategories($idProductConcreteAlternative);

        if (empty($productConcreteCategories)) {
            return $productAlternativeTableItemTransfer;
        }

        return $productAlternativeTableItemTransfer
            ->setCategories($productConcreteCategories);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getProductAbstractCategories(int $idProductAbstract): array
    {
        return $this
            ->productCategoryQueryContainer
            ->queryProductCategoryMappings()
            ->filterByFkProductAbstract($idProductAbstract)
            ->innerJoinSpyCategory()
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, static::COL_CATEGORY)
            ->select(static::COL_CATEGORY)
            ->find();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array
     */
    protected function getProductConcreteCategories(int $idProductConcrete): array
    {
        $idProductAbstractParent = $this
            ->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->innerJoinSpyProductAbstract()
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->findOne();

        return $this->getProductAbstractCategories($idProductAbstractParent);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductConcreteData(int $idProductConcrete): SpyProduct
    {
        return $this
            ->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->innerJoinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($this->getCurrentLocaleId())
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->findOne();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractData(int $idProductAbstract): SpyProductAbstract
    {
        return $this
            ->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->innerJoinSpyProductAbstractLocalizedAttributes()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($this->getCurrentLocaleId())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyProductAbstract
     *
     * @return bool
     */
    protected function getProductAbstractStatus(SpyProductAbstract $spyProductAbstract): bool
    {
        $isActive = false;

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $spyProduct */
        foreach ($spyProductAbstract->getSpyProducts() as $spyProduct) {
            if ($spyProduct->isActive()) {
                $isActive = true;
                break;
            }
        }

        return $isActive;
    }

    /**
     * @return int
     */
    protected function getCurrentLocaleId(): int
    {
        return $this
            ->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();
    }
}

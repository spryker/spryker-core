<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerInterface;

class ProductAlternativeListHydrator implements ProductAlternativeListHydratorInterface
{
    protected const COL_NAME = 'name';
    protected const COL_SKU = 'sku';
    protected const COL_STATUS = 'status';
    protected const COL_CATEGORY = 'category';

    protected const FIELD_PRODUCT_TYPE_ABSTRACT = 'Abstract';
    protected const FIELD_PRODUCT_TYPE_CONCRETE = 'Concrete';

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
     * @param \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface $productCategoryQueryContainer
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductAlternativeToProductQueryContainerInterface $productQueryContainer,
        ProductAlternativeToProductCategoryQueryContainerInterface $productCategoryQueryContainer,
        ProductAlternativeToLocaleFacadeInterface $localeFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function hydrateProductAbstractListItem(
        int $idProductAbstractAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAlternativeListItemTransfer = $this
            ->hydrateListItemWithProductAbstractAlternativeData(
                $idProductAbstractAlternative,
                $productAlternativeListItemTransfer
            );

        $productAlternativeListItemTransfer = $this
            ->hydrateListItemWithProductAbstractCategoryData(
                $idProductAbstractAlternative,
                $productAlternativeListItemTransfer
            );

        return $productAlternativeListItemTransfer;
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function hydrateProductConcreteListItem(
        int $idProductConcreteAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAlternativeListItemTransfer = $this
            ->hydrateListItemWithProductConcreteAlternativeData(
                $idProductConcreteAlternative,
                $productAlternativeListItemTransfer
            );

        $productAlternativeListItemTransfer = $this
            ->hydrateListItemWithProductConcreteCategoryData(
                $idProductConcreteAlternative,
                $productAlternativeListItemTransfer
            );

        return $productAlternativeListItemTransfer;
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateListItemWithProductAbstractAlternativeData(
        int $idProductAbstractAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAbstractAlternative = $this
            ->getProductAbstractData($idProductAbstractAlternative);

        if (!$productAbstractAlternative) {
            return $productAlternativeListItemTransfer;
        }

        return $productAlternativeListItemTransfer
            ->setType(static::FIELD_PRODUCT_TYPE_ABSTRACT)
            ->setName($productAbstractAlternative->getVirtualColumn(static::COL_NAME))
            ->setSku($productAbstractAlternative->getSku())
            ->setStatus(
                $this->getProductAbstractStatus($productAbstractAlternative)
            );
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateListItemWithProductConcreteAlternativeData(
        int $idProductConcreteAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productConcreteAlternative = $this
            ->getProductConcreteData($idProductConcreteAlternative);

        if (!$productConcreteAlternative) {
            return $productAlternativeListItemTransfer;
        }

        return $productAlternativeListItemTransfer
            ->setType(static::FIELD_PRODUCT_TYPE_CONCRETE)
            ->setName($productConcreteAlternative->getVirtualColumn(static::COL_NAME))
            ->setSku($productConcreteAlternative->getSku())
            ->setStatus($productConcreteAlternative->getIsActive());
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $ProductAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateListItemWithProductAbstractCategoryData(
        int $idProductAbstractAlternative,
        ProductAlternativeListItemTransfer $ProductAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAbstractCategories = $this
            ->getProductAbstractCategories($idProductAbstractAlternative);

        if (empty($productAbstractCategories)) {
            return $ProductAlternativeListItemTransfer;
        }

        return $ProductAlternativeListItemTransfer
            ->setCategories($productAbstractCategories);
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $ProductAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateListItemWithProductConcreteCategoryData(
        int $idProductConcreteAlternative,
        ProductAlternativeListItemTransfer $ProductAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productConcreteCategories = $this
            ->getProductConcreteCategories($idProductConcreteAlternative);

        if (empty($productConcreteCategories)) {
            return $ProductAlternativeListItemTransfer;
        }

        return $ProductAlternativeListItemTransfer
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
            ->find()
            ->toArray();
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

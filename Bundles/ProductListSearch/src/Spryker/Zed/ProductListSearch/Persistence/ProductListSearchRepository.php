<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchPersistenceFactory getFactory()
 */
class ProductListSearchRepository extends AbstractRepository implements ProductListSearchRepositoryInterface
{
    public const COL_CONCRETE_PRODUCT_COUNT = 'concrete_product_count';
    public const COL_ID_PRODUCT_ABSTRACT = 'col_id_product_abstract';
    public const COL_TYPE = 'col_type';
    public const COL_ID_PRODUCT_LIST = 'col_id_product_list';

    /**
     * @return int
     */
    public function getValueForWhitelistType(): int
    {
        return $this->getEnumValueForListType(SpyProductListTableMap::COL_TYPE_WHITELIST);
    }

    /**
     * @return int
     */
    public function getValueForBlacklistType(): int
    {
        return $this->getEnumValueForListType(SpyProductListTableMap::COL_TYPE_BLACKLIST);
    }

    /**
     * @param string $listType
     *
     * @return int
     */
    protected function getEnumValueForListType(string $listType): int
    {
        return array_search(
            $listType,
            SpyProductListTableMap::getValueSet(SpyProductListTableMap::COL_TYPE)
        );
    }

    /**
     * @uses SpyProductQuery
     *
     * @param int[] $concreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->getProductQuery()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        return $productQuery
            ->filterByIdProduct_In($concreteIds)
            ->find()
            ->toArray();
    }

    /**
     * @module ProductCategory
     *
     * @param array $categoryIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productCategoryQuery */
        $productCategoryQuery = $this->getFactory()
            ->getProductCategoryPropelQuery()
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT);

        return $productCategoryQuery
            ->filterByFkCategory_In($categoryIds)
            ->distinct()
            ->find()
            ->toArray();
    }

    /**
     * @deprecated Use \Spryker\Zed\ProductList\Persistence\ProductListRepository::getProductConcreteCountByProductAbstractIds instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductConcreteCountByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->addAsColumn(static::COL_CONCRETE_PRODUCT_COUNT, sprintf('COUNT(%s)', SpyProductTableMap::COL_ID_PRODUCT))
            ->addAsColumn(static::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->select([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->groupBy([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->find()
            ->toArray(static::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @deprecated Use \Spryker\Zed\ProductList\Persistence\ProductListRepository::getCategoryProductList instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getCategoryProductList(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductListCategoryPropelQuery()
            ->select([
                SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->withColumn(SpyProductListTableMap::COL_TYPE, static::COL_TYPE)
            ->withColumn(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST, static::COL_ID_PRODUCT_LIST)
            ->withColumn(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->innerJoinWithSpyCategory()
            ->useSpyCategoryQuery()
            ->innerJoinWithSpyProductCategory()
            ->useSpyProductCategoryQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->endUse()
            ->innerJoinWithSpyProductList()
            ->groupBy([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST,
                SpyProductListTableMap::COL_TYPE,
            ])
            ->find()
            ->toArray();
    }

    /**
     * @deprecated Use \Spryker\Zed\ProductList\Persistence\ProductListRepository::getProductListsByIdProductAbstractIn instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductList(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductListProductConcretePropelQuery()
            ->addAsColumn(ProductListSearchRepository::COL_CONCRETE_PRODUCT_COUNT, sprintf('COUNT(%s)', SpyProductListProductConcreteTableMap::COL_FK_PRODUCT))
            ->addAsColumn(ProductListSearchRepository::COL_ID_PRODUCT_LIST, SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->addAsColumn(ProductListSearchRepository::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(ProductListSearchRepository::COL_TYPE, SpyProductListTableMap::COL_TYPE)
            ->select([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->innerJoinWithSpyProduct()
            ->useSpyProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->innerJoinWithSpyProductList()
            ->groupBy([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductListTableMap::COL_TYPE,
            ])
            ->find()
            ->toArray();
    }
}

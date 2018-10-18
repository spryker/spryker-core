<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ProductList\Persistence\Mapper\ProductListMapperInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductList\Persistence\ProductListPersistenceFactory getFactory()
 */
class ProductListRepository extends AbstractRepository implements ProductListRepositoryInterface
{
    public const COL_CONCRETE_PRODUCT_COUNT = 'concrete_product_count';
    public const COL_ID_PRODUCT_ABSTRACT = 'col_id_product_abstract';
    public const COL_TYPE = 'col_type';
    public const COL_ID_PRODUCT_LIST = 'col_id_product_list';

    /**
     * @param int $idProductList
     *
     * @return int[]
     */
    public function getRelatedCategoryIdsByIdProductList(int $idProductList): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery $productListCategoryQuery */
        $productListCategoryQuery = $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_CATEGORY);

        return $productListCategoryQuery
                ->findByFkProductList($idProductList)
                ->toArray();
    }

    /**
     * @param int $idProductList
     *
     * @return int[]
     */
    public function getRelatedProductConcreteIdsByIdProductList(int $idProductList): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery $productListProductConcreteQuery */
        $productListProductConcreteQuery = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT);

        return $productListProductConcreteQuery
            ->findByFkProductList($idProductList)
            ->toArray();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getAbstractProductBlacklistIds(int $idProductAbstract): array
    {
        $blacklistIds = [];
        $blacklistIds = array_merge(
            $blacklistIds,
            $this->getProductBlacklistIdsByIdAbstractProduct($idProductAbstract),
            $this->getCategoryBlacklistIdsByIdAbstractProduct($idProductAbstract)
        );

        return array_unique($blacklistIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getAbstractProductWhitelistIds(int $idProductAbstract): array
    {
        $whitelistIds = [];
        $whitelistIds = array_merge(
            $whitelistIds,
            $this->getProductWhitelistIdsByIdAbstractProduct($idProductAbstract),
            $this->getCategoryWhitelistIdsByIdAbstractProduct($idProductAbstract)
        );

        return array_unique($whitelistIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return array_unique($this->getCategoryWhitelistIdsByIdAbstractProduct($idProductAbstract));
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getConcreteProductBlacklistIds(int $idProductConcrete): array
    {
        return $this->getConcreteProductWhiteOrBlacklistIds(
            $idProductConcrete,
            SpyProductListTableMap::COL_TYPE_BLACKLIST
        );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getConcreteProductWhitelistIds(int $idProductConcrete): array
    {
        return $this->getConcreteProductWhiteOrBlacklistIds(
            $idProductConcrete,
            SpyProductListTableMap::COL_TYPE_WHITELIST
        );
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->getProductQuery()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_ID_PRODUCT]);

        return $productQuery
            ->filterByIdProduct_In($productConcreteIds)
            ->find()
            ->toKeyValue(SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductListIdsByProductConcreteIdsIn(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery $productListProductConcreteQuery */
        $productListProductConcreteQuery = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT,
            ])
            ->withColumn(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST, static::COL_ID_PRODUCT_LIST)
            ->withColumn(SpyProductListTableMap::COL_TYPE, static::COL_TYPE);

        return $productListProductConcreteQuery
            ->filterByFkProduct_In($productConcreteIds)
            ->innerJoinSpyProductList()
            ->groupByFkProductList()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductConcrete
     * @param string $listType
     *
     * @return int[]
     */
    protected function getConcreteProductWhiteOrBlacklistIds(int $idProductConcrete, string $listType): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery $productListProductConcreteQuery */
        $productListProductConcreteQuery = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST);

        return $productListProductConcreteQuery
            ->filterByFkProduct($idProductConcrete)
            ->useSpyProductListQuery(null, Criteria::LEFT_JOIN)
                ->filterByType($listType)
            ->endUse()
            ->groupByFkProductList()
            ->find()
            ->toArray();
    }

    /**
     * @uses SpyProductCategoryQuery
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getCategoryBlacklistIdsByIdAbstractProduct(int $idProductAbstract): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery $productListCategoryQuery */
        $productListCategoryQuery = $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST);

        return $productListCategoryQuery
            ->useSpyCategoryQuery()
                ->useSpyProductCategoryQuery()
                    ->filterByFkProductAbstract($idProductAbstract)
                ->endUse()
            ->endUse()
            ->useSpyProductListQuery()
                ->filterByType(SpyProductListTableMap::COL_TYPE_BLACKLIST)
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getProductBlacklistIdsByIdAbstractProduct(int $idProductAbstract): array
    {
        $countConcreteProduct = SpyProductQuery::create()->filterByFkProductAbstract($idProductAbstract)->count();

        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery $productListProductConcreteQuery */
        $productListProductConcreteQuery = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST);

        return $productListProductConcreteQuery
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->useSpyProductListQuery(null, Criteria::LEFT_JOIN)
                ->filterByType(SpyProductListTableMap::COL_TYPE_BLACKLIST)
            ->endUse()
            ->groupByFkProductList()
            ->having('COUNT(' . SpyProductListProductConcreteTableMap::COL_FK_PRODUCT . ') = ?', $countConcreteProduct)
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getProductWhitelistIdsByIdAbstractProduct(int $idProductAbstract): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery $productListProductConcreteQuery */
        $productListProductConcreteQuery = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST);

        return $productListProductConcreteQuery
            ->useSpyProductQuery(null, Criteria::INNER_JOIN)
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->useSpyProductListQuery(null, Criteria::INNER_JOIN)
                ->filterByType(SpyProductListTableMap::COL_TYPE_WHITELIST)
            ->endUse()
            ->groupByFkProductList()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getCategoryWhitelistIdsByIdAbstractProduct(int $idProductAbstract): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery $productListCategoryQuery */
        $productListCategoryQuery = $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST);

        return $productListCategoryQuery
            ->useSpyCategoryQuery()
                ->useSpyProductCategoryQuery()
                    ->filterByFkProductAbstract($idProductAbstract)
                ->endUse()
            ->endUse()
            ->useSpyProductListQuery()
                ->filterByType(SpyProductListTableMap::COL_TYPE_WHITELIST)
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @param int $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getProductListById(int $idProductList): ProductListTransfer
    {
        $productListTransfer = new ProductListTransfer();
        $query = $this->getFactory()
            ->createProductListQuery()
            ->filterByIdProductList($idProductList);
        $spyProductListEntityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getMapper()
            ->mapEntityTransferToProductListTransfer($spyProductListEntityTransfer, $productListTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductList\Persistence\Mapper\ProductListMapperInterface
     */
    protected function getMapper(): ProductListMapperInterface
    {
        return $this->getFactory()
            ->createProductListMapper();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->getFactory()
            ->createProductListQuery()
            ->filterByKey($key)
            ->exists();
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array
    {
        return array_unique(
            array_merge(
                $this->getProductAbstractIdsRelatedToProductConcrete($productListIds),
                $this->getProductAbstractIdsRelatedToCategories($productListIds)
            )
        );
    }

    /**
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
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getCategoryProductList(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductListCategoryQuery()
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
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductListsByIdProductAbstractIn(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->addAsColumn(static::COL_CONCRETE_PRODUCT_COUNT, sprintf('COUNT(%s)', SpyProductListProductConcreteTableMap::COL_FK_PRODUCT))
            ->addAsColumn(static::COL_ID_PRODUCT_LIST, SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->addAsColumn(static::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(static::COL_TYPE, SpyProductListTableMap::COL_TYPE)
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

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    protected function getProductAbstractIdsRelatedToProductConcrete(array $productListIds): array
    {
        return $this->getFactory()
            ->createProductListQuery()
            ->filterByIdProductList_In($productListIds)
            ->useSpyProductListProductConcreteQuery(null, Criteria::INNER_JOIN)
                ->useSpyProductQuery(null, Criteria::INNER_JOIN)
                    ->leftJoinSpyProductAbstract()
                ->endUse()
            ->endUse()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find()
            ->toArray();
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    protected function getProductAbstractIdsRelatedToCategories(array $productListIds): array
    {
        return $this->getFactory()
            ->createProductListQuery()
            ->filterByIdProductList_In($productListIds)
            ->useSpyProductListCategoryQuery(null, Criteria::INNER_JOIN)
                ->useSpyCategoryQuery(null, Criteria::INNER_JOIN)
                    ->leftJoinSpyProductCategory()
                ->endUse()
            ->endUse()
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find()
            ->toArray();
    }
}

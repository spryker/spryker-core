<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Generated\Shared\Transfer\ProductListCriteriaTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductList\Persistence\ProductListPersistenceFactory getFactory()
 */
class ProductListRepository extends AbstractRepository implements ProductListRepositoryInterface
{
    /**
     * @var string
     */
    public const COL_CONCRETE_PRODUCT_COUNT = 'concrete_product_count';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'col_id_product_abstract';

    /**
     * @var string
     */
    public const COL_TYPE = 'col_type';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_LIST = 'col_id_product_list';

    /**
     * @param int $idProductList
     *
     * @return array<int>
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
     * @return array<int>
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
     * @return array<int>
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        $blacklistIds = [];
        $blacklistIds = array_merge(
            $blacklistIds,
            $this->getProductBlacklistIdsByIdAbstractProduct($idProductAbstract),
            $this->getCategoryBlacklistIdsByIdAbstractProduct($idProductAbstract),
        );

        return array_unique($blacklistIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getAbstractProductWhitelistIds(int $idProductAbstract): array
    {
        $whitelistIds = [];
        $whitelistIds = array_merge(
            $whitelistIds,
            $this->getProductWhitelistIdsByIdAbstractProduct($idProductAbstract),
            $this->getCategoryWhitelistIdsByIdAbstractProduct($idProductAbstract),
        );

        return array_unique($whitelistIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array
    {
        return array_unique($this->getCategoryWhitelistIdsByIdAbstractProduct($idProductAbstract));
    }

    /**
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductListIdsByProductIds(array $productIds): array
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
            ->filterByFkProduct_In($productIds)
            ->innerJoinSpyProductList()
            ->groupByFkProductList()
            ->groupByFkProduct()
            ->find()
            ->toArray();
    }

    /**
     * @param array<string> $productConcreteSkus
     * @param array<int> $blackListIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusInBlacklists(array $productConcreteSkus, array $blackListIds): array
    {
        return $this->getConcreteProductSkusInList(
            $productConcreteSkus,
            SpyProductListTableMap::COL_TYPE_BLACKLIST,
            $blackListIds,
        );
    }

    /**
     * @param array<string> $productConcreteSkus
     * @param array<int> $whiteListIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusInWhitelists(array $productConcreteSkus, array $whiteListIds): array
    {
        return $this->getConcreteProductSkusInList(
            $productConcreteSkus,
            SpyProductListTableMap::COL_TYPE_WHITELIST,
            $whiteListIds,
        );
    }

    /**
     * @param int $idProduct
     * @param string $listType
     *
     * @return array<int>
     */
    public function getProductConcreteProductListIdsForType(int $idProduct, string $listType): array
    {
        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->filterByFkProduct($idProduct)
            ->useSpyProductListQuery(null, Criteria::LEFT_JOIN)
                ->filterByType($listType)
            ->endUse()
            ->groupByFkProductList()
            ->find()
            ->toArray();
    }

    /**
     * @module Product
     *
     * @param array<string> $productConcreteSkus
     * @param string $listType
     * @param array $productListIds
     *
     * @return array<string>
     */
    protected function getConcreteProductSkusInList(array $productConcreteSkus, string $listType, array $productListIds): array
    {
        if (!$productConcreteSkus) {
            return [];
        }

        if (!$productListIds) {
            return [];
        }

        $productConcreteSkusInList = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->filterByFkProductList_In($productListIds)
            ->useSpyProductQuery()
                ->filterBySku_In($productConcreteSkus)
            ->endUse()
            ->useSpyProductListQuery(null, Criteria::LEFT_JOIN)
                ->filterByType($listType)
            ->endUse()
            ->select(SpyProductTableMap::COL_SKU)
            ->find()
            ->toArray();

        $productConcreteSkusInListAndCategory = $this->getFactory()
            ->createProductListCategoryQuery()
            ->filterByFkProductList_In($productListIds)
            ->useSpyProductListQuery()
                ->filterByType($listType)
            ->endUse()
            ->useSpyCategoryQuery()
                ->useSpyProductCategoryQuery()
                    ->useSpyProductAbstractQuery()
                        ->useSpyProductQuery()
                            ->filterBySku_In($productConcreteSkus)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->select(SpyProductTableMap::COL_SKU)
            ->find()
            ->toArray();

        return array_merge($productConcreteSkusInList, $productConcreteSkusInListAndCategory);
    }

    /**
     * @uses SpyProductCategoryQuery
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
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
     * @module Product
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
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
     * @module Product
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
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
     * @return array<int>
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
        $productListEntityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if (!$productListEntityTransfer) {
            return $productListTransfer;
        }

        return $this->getFactory()
            ->createProductListMapper()
            ->mapEntityTransferToProductListTransfer($productListEntityTransfer, $productListTransfer);
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
     * @module Category
     * @module Product
     * @module ProductCategory
     *
     * @param int $idProduct
     * @param string $listType
     *
     * @return array<int>
     */
    public function getProductConcreteProductListIdsRelatedToCategoriesForType(int $idProduct, string $listType): array
    {
        return $this->getFactory()
            ->createProductListQuery()
            ->filterByType($listType)
            ->useSpyProductListCategoryQuery()
                ->useSpyCategoryQuery()
                    ->useSpyProductCategoryQuery()
                        ->useSpyProductAbstractQuery()
                            ->useSpyProductQuery()
                                ->filterByIdProduct($idProduct)
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->select(SpyProductListTableMap::COL_ID_PRODUCT_LIST)
            ->find()
            ->toArray();
    }

    /**
     * @module Category
     * @module ProductCategory
     *
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductListByProductAbstractIdsThroughCategory(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductListCategoryQuery()
            ->select([
                SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->withColumn(SpyProductListTableMap::COL_TYPE, static::COL_TYPE)
            ->withColumn(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST, static::COL_ID_PRODUCT_LIST)
            ->withColumn(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->distinct()
            ->innerJoinWithSpyCategory()
            ->useSpyCategoryQuery()
                ->innerJoinWithSpyProductCategory()
                ->useSpyProductCategoryQuery()
                    ->filterByFkProductAbstract_In($productAbstractIds)
                ->endUse()
            ->endUse()
            ->innerJoinWithSpyProductList()
            ->find()
            ->toArray();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductBlacklistsByProductAbstractIds(array $productAbstractIds): array
    {
        $spyProductTableAlias = 'spy_product_alias';
        $productFilterJoin = new Join(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::INNER_JOIN);
        $productFilterJoin->setRightTableAlias($spyProductTableAlias);

        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->addAsColumn(static::COL_ID_PRODUCT_LIST, SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->addAsColumn(static::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(static::COL_TYPE, SpyProductListTableMap::COL_TYPE)
            ->select([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->useSpyProductQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->addJoinObject($productFilterJoin)
            ->useSpyProductListQuery(null, Criteria::LEFT_JOIN)
                ->filterByType(SpyProductListTableMap::COL_TYPE_BLACKLIST)
            ->endUse()
            ->groupBy([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->having(sprintf(
                'COUNT(DISTINCT %s) = COUNT(DISTINCT %s)',
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::alias($spyProductTableAlias, SpyProductTableMap::COL_ID_PRODUCT),
            ))
            ->setFormatter(SimpleArrayFormatter::class)
            ->find()
            ->toArray();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getProductWhiteListsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->addAsColumn(static::COL_ID_PRODUCT_LIST, SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->addAsColumn(static::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addAsColumn(static::COL_TYPE, SpyProductListTableMap::COL_TYPE)
            ->select([
                SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
            ])
            ->distinct()
            ->useSpyProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse()
            ->useSpyProductListQuery(null, Criteria::INNER_JOIN)
                ->filterByType(SpyProductListTableMap::COL_TYPE_WHITELIST)
            ->endUse()
            ->setFormatter(SimpleArrayFormatter::class)
            ->find()
            ->toArray();
    }

    /**
     * @module Product
     *
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsRelatedToProductConcrete(array $productListIds): array
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
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsRelatedToCategories(array $productListIds): array
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

    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsRelatedToProductLists(array $productListIds): array
    {
        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->filterByFkProductList_In($productListIds)
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT)
            ->find()
            ->toArray();
    }

    /**
     * @module Category
     * @module Product
     * @module ProductCategory
     *
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsRelatedToProductListsCategories(array $productListIds): array
    {
        return $this->getFactory()
            ->createProductListQuery()
            ->filterByIdProductList_In($productListIds)
            ->useSpyProductListCategoryQuery()
                ->useSpyCategoryQuery()
                    ->innerJoinSpyProductCategory()
                    ->useSpyProductCategoryQuery()
                        ->innerJoinSpyProductAbstract()
                        ->useSpyProductAbstractQuery()
                            ->innerJoinSpyProduct()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCriteriaTransfer $productListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getProductListCollection(ProductListCriteriaTransfer $productListCriteriaTransfer): ProductListCollectionTransfer
    {
        $productListCollectionTransfer = new ProductListCollectionTransfer();
        $productListQuery = $this->getFactory()->createProductListQuery();

        $paginationTransfer = $productListCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $productListQuery = $this->applyProductListPagination($productListQuery, $paginationTransfer);
            $productListCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createProductListMapper()
            ->mapProductListEntitiesToProductListCollectionTransfer(
                $productListQuery->find(),
                $productListCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductListQuery $productListQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected function applyProductListPagination(
        SpyProductListQuery $productListQuery,
        PaginationTransfer $paginationTransfer
    ): SpyProductListQuery {
        $paginationTransfer->setNbResults($productListQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productListQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $productListQuery;
    }
}

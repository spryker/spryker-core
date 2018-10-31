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
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array
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
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getConcreteProductBlacklistIds(int $idProduct): array
    {
        return $this->getConcreteProductListIdsForType(
            $idProduct,
            SpyProductListTableMap::COL_TYPE_BLACKLIST
        );
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getConcreteProductWhitelistIds(int $idProduct): array
    {
        return $this->getConcreteProductListIdsForType(
            $idProduct,
            SpyProductListTableMap::COL_TYPE_WHITELIST
        );
    }

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $blackListIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusInBlacklists(array $productConcreteSkus, array $blackListIds): array
    {
        return $this->getConcreteProductSkusInList(
            $productConcreteSkus,
            SpyProductListTableMap::COL_TYPE_BLACKLIST,
            $blackListIds
        );
    }

    /**
     * @param string[] $productConcreteSkus
     * @param int[] $whiteListIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusInWhitelists(array $productConcreteSkus, array $whiteListIds): array
    {
        return $this->getConcreteProductSkusInList(
            $productConcreteSkus,
            SpyProductListTableMap::COL_TYPE_WHITELIST,
            $whiteListIds
        );
    }

    /**
     * @param int $idProduct
     * @param string $listType
     *
     * @return int[]
     */
    protected function getConcreteProductListIdsForType(int $idProduct, string $listType): array
    {
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery $productListProductConcreteQuery */
        $productListProductConcreteQuery = $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST);

        return $productListProductConcreteQuery
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
     * @param string[] $productConcreteSkus
     * @param string $listType
     * @param array $productListIds
     *
     * @return string[]
     */
    protected function getConcreteProductSkusInList(array $productConcreteSkus, string $listType, array $productListIds): array
    {
        if (empty($productConcreteSkus)) {
            return [];
        }

        if (empty($productListIds)) {
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
     * @module Product
     *
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
     * @module Product
     *
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
     * @module Product
     *
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

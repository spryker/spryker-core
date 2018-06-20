<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
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
        return $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_CATEGORY)
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
        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT)
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
     * @uses SpyProductCategoryQuery
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function getCategoryBlacklistIdsByIdAbstractProduct(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST)
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

        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
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
        $countConcreteProduct = SpyProductQuery::create()->filterByFkProductAbstract($idProductAbstract)->count();

        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST)
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->useSpyProductListQuery(null, Criteria::LEFT_JOIN)
                ->filterByType(SpyProductListTableMap::COL_TYPE_WHITELIST)
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
    protected function getCategoryWhitelistIdsByIdAbstractProduct(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_PRODUCT_LIST)
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
}

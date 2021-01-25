<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use InvalidArgumentException;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Stock\Persistence\StockPersistenceFactory getFactory()
 */
class StockRepository extends AbstractRepository implements StockRepositoryInterface
{
    /**
     * @return string[]
     */
    public function getStockNames(): array
    {
        $stockQuery = $this->getFactory()
            ->createStockQuery()
            ->select(SpyStockTableMap::COL_NAME);

        return $stockQuery->find()->getData();
    }

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getStockNamesForStore(string $storeName): array
    {
        $stockStoreQuery = $this->getFactory()
            ->createStockStoreQuery()
            ->joinWithStock()
            ->useStoreQuery()
                ->filterByName($storeName)
            ->endUse()
            ->select([SpyStockTableMap::COL_NAME]);

        return $stockStoreQuery->find()->getData();
    }

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockById(int $idStock): ?StockTransfer
    {
        $stockEntity = $this->getFactory()
            ->createStockQuery()
            ->filterByIdStock($idStock)
            ->findOne();

        if ($stockEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createStockMapper()
            ->mapStockEntityToStockTransfer($stockEntity, new StockTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStocksWithRelatedStoresByCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): array
    {
        $stockQuery = $this->getFactory()
            ->createStockQuery()
            ->leftJoinWithStockStore()
            ->useStockStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithStore()
            ->endUse();
        $stockQuery = $this->applyStockQueryFilters($stockQuery, $stockCriteriaFilterTransfer);

        return $this->getFactory()
            ->createStockMapper()
            ->mapStockEntitiesToStockTransfers($stockQuery->find()->getArrayCopy());
    }

    /**
     * @param string $stockName
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockByName(string $stockName): ?StockTransfer
    {
        $stockEntity = $this->getFactory()
            ->createStockQuery()
            ->filterByName($stockName)
            ->findOne();

        if ($stockEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createStockMapper()
            ->mapStockEntityToStockTransfer($stockEntity, new StockTransfer());
    }

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByIdStock(int $idStock): array
    {
        $stockProductQuery = $this->getFactory()
            ->createStockProductQuery()
            ->leftJoinWithStock()
            ->leftJoinWithSpyProduct()
            ->filterByFkStock($idStock);

        return $this->getFactory()
            ->createStockProductMapper()
            ->mapStockProductEntitiesToStockProductTransfers($stockProductQuery->find()->getArrayCopy());
    }

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdStock(int $idStock): StoreRelationTransfer
    {
        $stockStoreQuery = $this->getFactory()
            ->createStockStoreQuery()
            ->leftJoinWithStore()
            ->filterByFkStock($idStock);

        return $this->getFactory()
            ->createStockStoreRelationMapper()
            ->mapStockStoreEntitiesToStoreRelationTransfer(
                $idStock,
                $stockStoreQuery->find()->getArrayCopy(),
                new StoreRelationTransfer()
            );
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductByProductAbstractSkuForStore(string $abstractSku, StoreTransfer $storeTransfer): array
    {
        $stockProductEntities = $this->queryStockProductByProductAbstractSkuAndStore($abstractSku, $storeTransfer)->find();

        return $this->getFactory()
            ->createStockProductMapper()
            ->mapStockProductEntitiesToStockProductTransfers($stockProductEntities->getData());
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductAbstractNeverOutOfStockForStore(string $abstractSku, StoreTransfer $storeTransfer): bool
    {
        return $this->queryStockProductByProductAbstractSkuAndStore($abstractSku, $storeTransfer)
            ->filterByIsNeverOutOfStock(true)
            ->exists();
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findProductStocksForStore(string $concreteSku, StoreTransfer $storeTransfer): array
    {
        $stockProductEntities = $this->queryStockProductByProductConcreteSkuAndStore($concreteSku, $storeTransfer)->find();

        if ($stockProductEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createStockProductMapper()
            ->mapStockProductEntitiesToStockProductTransfers($stockProductEntities->getArrayCopy());
    }

    /**
     * @param string $concreteSku
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByProductConcreteSku(string $concreteSku): array
    {
        $stockProductEntities = $this->getFactory()
            ->createStockProductQuery()
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->filterBySku($concreteSku)
            ->endUse()
            ->useStockQuery(null, Criteria::LEFT_JOIN)
                ->filterByIsActive(true)
            ->endUse()
            ->find();

        if ($stockProductEntities->count() === 0) {
            throw new InvalidArgumentException('No stock set for this sku');
        }

        return $this->getFactory()
            ->createStockProductMapper()
            ->mapStockProductEntitiesToStockProductTransfers($stockProductEntities->getArrayCopy());
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    protected function queryStockProductByProductAbstractSkuAndStore(string $abstractSku, StoreTransfer $storeTransfer): SpyStockProductQuery
    {
        return $this->getFactory()
            ->createStockProductQuery()
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                    ->filterBySku($abstractSku)
                ->endUse()
            ->endUse()
            ->useStockQuery(null, Criteria::LEFT_JOIN)
                ->filterByIsActive(true)
                ->useStockStoreQuery(null, Criteria::LEFT_JOIN)
                    ->useStoreQuery(null, Criteria::LEFT_JOIN)
                        ->filterByName($storeTransfer->getName())
                    ->endUse()
                ->endUse()
            ->endUse();
    }

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    protected function queryStockProductByProductConcreteSkuAndStore(string $concreteSku, StoreTransfer $storeTransfer): SpyStockProductQuery
    {
        return $this->getFactory()
            ->createStockProductQuery()
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->filterBySku($concreteSku)
            ->endUse()
            ->useStockQuery(null, Criteria::LEFT_JOIN)
                ->filterByIsActive(true)
                ->useStockStoreQuery(null, Criteria::LEFT_JOIN)
                    ->useStoreQuery(null, Criteria::LEFT_JOIN)
                        ->filterByName($storeTransfer->getName())
                    ->endUse()
                ->endUse()
            ->endUse();
    }

    /**
     * @module Store
     * @module Product
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWhereProductStockIsDefined(string $sku): array
    {
        $query = $this->getFactory()
            ->getStoreQuery()
            ->useStockStoreQuery(null, Criteria::LEFT_JOIN)
                ->useStockQuery(null, Criteria::LEFT_JOIN)
                    ->useStockProductQuery(null, Criteria::LEFT_JOIN)
                        ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                            ->filterBySku($sku)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->distinct();

        return $this->getFactory()
            ->createStoreMapper()
            ->mapStoreEntitiesToStoreTransfers($query->find()->getArrayCopy());
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected function applyStockQueryFilters(SpyStockQuery $stockQuery, StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): SpyStockQuery
    {
        if ($stockCriteriaFilterTransfer->getIsActive()) {
            $stockQuery->filterByIsActive(true);
        }

        if ($stockCriteriaFilterTransfer->getStoreNames()) {
            $stockQuery->useStockStoreQuery(null, Criteria::LEFT_JOIN)
                ->useStoreQuery(null, Criteria::LEFT_JOIN)
                    ->filterByName_In($stockCriteriaFilterTransfer->getStoreNames())
                ->endUse()
                ->endUse();
        }

        return $stockQuery;
    }
}

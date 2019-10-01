<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockStoreCriteriaFilterTransfer;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\Stock\Persistence\SpyStockStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Stock\Persistence\StockPersistenceFactory getFactory()
 */
class StockRepository extends AbstractRepository implements StockRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\StockTransfer[]
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
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStocksWithRelatedStoresByCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): array
    {
        $stockQuery = $this->getFactory()->createStockQuery();
        $stockQuery = $this->applyStockQueryFilters($stockQuery, $stockCriteriaFilterTransfer);
        /** @var \Orm\Zed\Stock\Persistence\SpyStock[] $stockEntities */
        $stockEntities = $stockQuery->find()->getArrayCopy();

        $stockStoreCriteriaFilterTransfer = (new StockStoreCriteriaFilterTransfer())
            ->setStoreNames($stockCriteriaFilterTransfer->getStoreNames());
        $stockEntities = $this->addRelatedStoresToStocks($stockEntities, $stockStoreCriteriaFilterTransfer);

        return $this->getFactory()
            ->createStockMapper()
            ->mapStockEntitiesToStockTransfers($stockEntities);
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock[] $stockEntities
     * @param \Generated\Shared\Transfer\StockStoreCriteriaFilterTransfer|null $stockStoreCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStock[]
     */
    protected function addRelatedStoresToStocks(array $stockEntities, ?StockStoreCriteriaFilterTransfer $stockStoreCriteriaFilterTransfer = null): array
    {
        if (!$stockStoreCriteriaFilterTransfer) {
            $stockStoreCriteriaFilterTransfer = new StockStoreCriteriaFilterTransfer();
        }
        $stockStoreCriteriaFilterTransfer->setStockIds($this->getStockIdsFromStockEntities($stockEntities));

        $stockStoreEntities = $this->getStockStoreEntitiesByCriteriaFilter($stockStoreCriteriaFilterTransfer);
        foreach ($stockEntities as $stockEntity) {
            foreach ($stockStoreEntities as $stockStoreEntity) {
                if ($stockEntity->getIdStock() === $stockStoreEntity->getFkStock()) {
                    $stockEntity->addSpyStockStore($stockStoreEntity);
                }
            }
        }

        return $stockEntities;
    }

    /**
     * @param \Generated\Shared\Transfer\StockStoreCriteriaFilterTransfer $stockStoreCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockStore[]
     */
    protected function getStockStoreEntitiesByCriteriaFilter(StockStoreCriteriaFilterTransfer $stockStoreCriteriaFilterTransfer): array
    {
        $stockStoreQuery = $this->getFactory()->createStockStoreQuery();
        $stockStoreQuery->joinWithStore();
        $stockStoreQuery = $this->applyStockStoreQueryFilters($stockStoreQuery, $stockStoreCriteriaFilterTransfer);

        return $stockStoreQuery->find()->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock[] $stockEntities
     *
     * @return int[]
     */
    protected function getStockIdsFromStockEntities(array $stockEntities): array
    {
        return array_map(function (SpyStock $stockEntity): int {
            return $stockEntity->getIdStock();
        }, $stockEntities);
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

        return $stockQuery;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockStoreQuery $stockStoreQuery
     * @param \Generated\Shared\Transfer\StockStoreCriteriaFilterTransfer $stockStoreCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockStoreQuery
     */
    protected function applyStockStoreQueryFilters(SpyStockStoreQuery $stockStoreQuery, StockStoreCriteriaFilterTransfer $stockStoreCriteriaFilterTransfer): SpyStockStoreQuery
    {
        if ($stockStoreCriteriaFilterTransfer->getStockIds()) {
            $stockStoreQuery->filterByFkStock_In($stockStoreCriteriaFilterTransfer->getStockIds());
        }

        if ($stockStoreCriteriaFilterTransfer->getStoreNames()) {
            $stockStoreQuery->useStoreQuery()
                ->filterByName_In($stockStoreCriteriaFilterTransfer->getStoreNames())
                ->endUse();
        }

        return $stockStoreQuery;
    }
}

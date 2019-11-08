<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use InvalidArgumentException;
use Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface;

class StockReader implements StockReaderInterface
{
    public const ERROR_STOCK_TYPE_UNKNOWN = 'stock type unknown';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected $stockRepository;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $queryContainer
     */
    public function __construct(
        StockRepositoryInterface $stockRepository,
        StockToStoreFacadeInterface $storeFacade,
        StockQueryContainerInterface $queryContainer
    ) {
        $this->stockRepository = $stockRepository;
        $this->storeFacade = $storeFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return string[]
     */
    public function getStockTypes(): array
    {
        $stockNames = $this->stockRepository->getStockNames();

        return array_combine($stockNames, $stockNames);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string[]
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer): array
    {
        $storeTransfer->requireName();
        $stockNames = $this->stockRepository->getStockNamesForStore($storeTransfer->getName());

        return array_combine($stockNames, $stockNames);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getAvailableWarehousesForStore(StoreTransfer $storeTransfer): array
    {
        $storeTransfer->requireName();

        $stockCriteriaFilterTransfer = (new StockCriteriaFilterTransfer())
            ->setIsActive(true)
            ->setStoreNames([$storeTransfer->getName()]);

        return $this->stockRepository->getStocksWithRelatedStoresByCriteriaFilter($stockCriteriaFilterTransfer);
    }

    /**
     * @return array
     */
    public function getWarehouseToStoreMapping(): array
    {
        $stockTransfers = $this->stockRepository->getStocksWithRelatedStoresByCriteriaFilter(new StockCriteriaFilterTransfer());

        $mapping = [];
        foreach ($stockTransfers as $stockTransfer) {
            $mapping[$stockTransfer->getName()] = $this->getStoreNamesFromStoreRelation($stockTransfer->getStoreRelation());
        }

        return $mapping;
    }

    /**
     * @return string[][]
     */
    public function getStoreToWarehouseMapping(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $stockTransfers = $this->stockRepository->getStocksWithRelatedStoresByCriteriaFilter(new StockCriteriaFilterTransfer());

        $mapping = array_fill_keys($this->getStoreNamesFromStoreTransferCollection($storeTransfers), []);
        foreach ($stockTransfers as $stockTransfer) {
            $mapping = $this->mapStockToStores($stockTransfer, $storeTransfers, $mapping);
        }

        return $mapping;
    }

    /**
     * @param string $stockType
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getStockTypeIdByName(string $stockType): int
    {
        $stockTransfer = $this->stockRepository->findStockByName($stockType);

        if (!$stockTransfer) {
            throw new InvalidArgumentException(self::ERROR_STOCK_TYPE_UNKNOWN);
        }

        return $stockTransfer->getIdStock();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return string[]
     */
    protected function getStoreNamesFromStoreRelation(StoreRelationTransfer $storeRelationTransfer): array
    {
        $storeNames = [];
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeName = $storeTransfer->getName();
            $storeNames[$storeName] = $storeName;
        }

        return $storeNames;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return string[]
     */
    protected function getStoreNamesFromStoreTransferCollection(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer): string {
            return $storeTransfer->getName();
        }, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     * @param string[][] $storeStockMapping
     *
     * @return string[][]
     */
    protected function mapStockToStores(StockTransfer $stockTransfer, array $storeTransfers, array $storeStockMapping): array
    {
        $relatedStoreNames = $this->getStoreNamesFromStoreRelation($stockTransfer->getStoreRelation());
        foreach ($storeTransfers as $storeTransfer) {
            if (in_array($storeTransfer->getName(), $relatedStoreNames, true)) {
                $storeStockMapping[$storeTransfer->getName()][] = $stockTransfer->getName();
            }
        }

        return $storeStockMapping;
    }
}

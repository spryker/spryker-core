<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use ArrayObject;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use InvalidArgumentException;
use Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface;

class StockReader implements StockReaderInterface
{
    public const ERROR_STOCK_TYPE_UNKNOWN = 'stock type unknown';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected $stockRepository;

    /**
     * @var \Spryker\Zed\Stock\Business\Stock\StockMapperInterface
     */
    protected $stockMapper;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\StockExtension\Dependency\Plugin\StockCollectionExpanderPluginInterface[]
     */
    protected $stockCollectionExpanderPlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param \Spryker\Zed\Stock\Business\Stock\StockMapperInterface $stockMapper
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\StockExtension\Dependency\Plugin\StockCollectionExpanderPluginInterface[] $stockCollectionExpanderPlugins
     */
    public function __construct(
        StockRepositoryInterface $stockRepository,
        StockMapperInterface $stockMapper,
        StockToStoreFacadeInterface $storeFacade,
        array $stockCollectionExpanderPlugins
    ) {
        $this->stockRepository = $stockRepository;
        $this->stockMapper = $stockMapper;
        $this->storeFacade = $storeFacade;
        $this->stockCollectionExpanderPlugins = $stockCollectionExpanderPlugins;
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

        $stockTransfers = $this->stockRepository->getStocksWithRelatedStoresByCriteriaFilter($stockCriteriaFilterTransfer);

        $stockCollectionTransfer = (new StockCollectionTransfer())->setStocks(new ArrayObject($stockTransfers));
        $stockCollectionTransfer = $this->executeStockCollectionExpanderPlugins($stockCollectionTransfer);

        return $stockCollectionTransfer->getStocks()->getArrayCopy();
    }

    /**
     * @return string[][]
     */
    public function getWarehouseToStoreMapping(): array
    {
        $stockTransfers = $this->stockRepository->getStocksWithRelatedStoresByCriteriaFilter(new StockCriteriaFilterTransfer());

        return $this->stockMapper->mapStoresToWarehouses($stockTransfers);
    }

    /**
     * @return string[][]
     */
    public function getStoreToWarehouseMapping(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $stockTransfers = $this->stockRepository->getStocksWithRelatedStoresByCriteriaFilter(new StockCriteriaFilterTransfer());

        return $this->stockMapper->mapWarehousesToStores($stockTransfers, $storeTransfers);
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
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    protected function executeStockCollectionExpanderPlugins(StockCollectionTransfer $stockCollectionTransfer): StockCollectionTransfer
    {
        foreach ($this->stockCollectionExpanderPlugins as $stockCollectionExpanderPlugin) {
            $stockCollectionTransfer = $stockCollectionExpanderPlugin->expandStockCollection($stockCollectionTransfer);
        }

        return $stockCollectionTransfer;
    }
}

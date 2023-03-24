<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Generator;

use ArrayObject;
use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\PickingList\Business\Creator\PickingListCreatorInterface;
use Spryker\Zed\PickingList\Business\Exception\PickingListStrategyNotFoundException;
use Spryker\Zed\PickingList\Business\Exception\WarehouseNotFoundException;
use Spryker\Zed\PickingList\Business\Extractor\WarehouseExtractorInterface;
use Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface;

class PickingListGenerator implements PickingListGeneratorInterface
{
    /**
     * @var string
     */
    protected const EXCEPTION_WAREHOUSE_NOT_FOUND = 'Warehouse with id "%s" not found.';

    /**
     * @var string
     */
    protected const EXCEPTION_PICKING_LIST_STRATEGY_NOT_FOUND = 'PickingList strategy "%s" not found.';

    /**
     * @var \Spryker\Zed\PickingList\Business\Creator\PickingListCreatorInterface
     */
    protected PickingListCreatorInterface $pickingListCreator;

    /**
     * @var \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface
     */
    protected PickingListGrouperInterface $pickingListGrouper;

    /**
     * @var \Spryker\Zed\PickingList\Business\Extractor\WarehouseExtractorInterface
     */
    protected WarehouseExtractorInterface $warehouseExtractor;

    /**
     * @var list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface>
     */
    protected array $pickingListGeneratorStrategyPlugins;

    /**
     * @param \Spryker\Zed\PickingList\Business\Creator\PickingListCreatorInterface $pickingListCreator
     * @param \Spryker\Zed\PickingList\Business\Grouper\PickingListGrouperInterface $pickingListGrouper
     * @param \Spryker\Zed\PickingList\Business\Extractor\WarehouseExtractorInterface $warehouseExtractor
     * @param list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface> $pickingListGeneratorStrategyPlugins
     */
    public function __construct(
        PickingListCreatorInterface $pickingListCreator,
        PickingListGrouperInterface $pickingListGrouper,
        WarehouseExtractorInterface $warehouseExtractor,
        array $pickingListGeneratorStrategyPlugins
    ) {
        $this->pickingListCreator = $pickingListCreator;
        $this->pickingListGrouper = $pickingListGrouper;
        $this->warehouseExtractor = $warehouseExtractor;
        $this->pickingListGeneratorStrategyPlugins = $pickingListGeneratorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function generatePickingLists(GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer): PickingListCollectionResponseTransfer
    {
        $this->assertRequiredOrderItemProperties($generatePickingListsRequestTransfer);

        $pickingListCollectionTransfer = $this->getPickingListCollectionTransfer(
            $generatePickingListsRequestTransfer,
        );

        $pickingListCollectionTransfer->requirePickingLists();

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPickingLists($pickingListCollectionTransfer->getPickingLists());

        return $this->pickingListCreator
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function getPickingListCollectionTransfer(
        GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
    ): PickingListCollectionTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection */
        $itemTransferCollection = $generatePickingListsRequestTransfer->getOrderItems();

        $stockTransferCollection = $this->warehouseExtractor
            ->extractWarehousesFromItemTransferCollection($itemTransferCollection);

        $stockTransferCollectionIndexedByIdWarehouse = $this->pickingListGrouper
            ->getStockTransferCollectionIndexedByIdWarehouse($stockTransferCollection);

        $itemTransferCollectionGroupedByIdWarehouse = $this->pickingListGrouper
            ->getItemTransferCollectionGroupedByIdWarehouse($itemTransferCollection);

        return $this->createPickingListCollectionTransfer(
            $stockTransferCollectionIndexedByIdWarehouse,
            $itemTransferCollectionGroupedByIdWarehouse,
        );
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollectionIndexedByIdWarehouse
     * @param array<int, list<\Generated\Shared\Transfer\ItemTransfer>> $itemTransferCollectionGroupedByIdWarehouse
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function createPickingListCollectionTransfer(
        array $stockTransferCollectionIndexedByIdWarehouse,
        array $itemTransferCollectionGroupedByIdWarehouse
    ): PickingListCollectionTransfer {
        $pickingListCollectionTransfer = new PickingListCollectionTransfer();
        foreach ($itemTransferCollectionGroupedByIdWarehouse as $idWarehouse => $itemTransferCollection) {
            $stockTransfer = $this->getStockTransfer($stockTransferCollectionIndexedByIdWarehouse, $idWarehouse);

            $pickingListCollectionTransfer = $this->addItemTransfersToPickingListCollectionTransfer(
                $itemTransferCollection,
                $stockTransfer,
                $pickingListCollectionTransfer,
            );
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function addItemTransfersToPickingListCollectionTransfer(
        array $itemTransferCollection,
        StockTransfer $stockTransfer,
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        $pickingListOrderItemGroupTransfer = $this->createPickingListOrderItemGroupTransfer(
            $itemTransferCollection,
            $stockTransfer,
        );

        $generatedPickingListCollectionTransfer = $this->executePickingListGeneratorStrategyPlugins(
            $pickingListOrderItemGroupTransfer,
        );

        return $this->mergePickingListCollectionTransfer(
            $generatedPickingListCollectionTransfer,
            $pickingListCollectionTransfer,
        );
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollectionIndexedByIdWarehouse
     * @param int $idWarehouse
     *
     * @throws \Spryker\Zed\PickingList\Business\Exception\WarehouseNotFoundException
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function getStockTransfer(
        array $stockTransferCollectionIndexedByIdWarehouse,
        int $idWarehouse
    ): StockTransfer {
        $stockTransfer = $stockTransferCollectionIndexedByIdWarehouse[$idWarehouse] ?? null;
        if (!$stockTransfer) {
            throw new WarehouseNotFoundException(
                sprintf(static::EXCEPTION_WAREHOUSE_NOT_FOUND, $idWarehouse),
            );
        }

        return $stockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @throws \Spryker\Zed\PickingList\Business\Exception\PickingListStrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function executePickingListGeneratorStrategyPlugins(
        PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
    ): PickingListCollectionTransfer {
        foreach ($this->pickingListGeneratorStrategyPlugins as $pickingListGeneratorStrategyPlugin) {
            if ($pickingListGeneratorStrategyPlugin->isApplicable($pickingListOrderItemGroupTransfer)) {
                return $pickingListGeneratorStrategyPlugin->generatePickingLists($pickingListOrderItemGroupTransfer);
            }
        }

        throw new PickingListStrategyNotFoundException(
            sprintf(
                static::EXCEPTION_PICKING_LIST_STRATEGY_NOT_FOUND,
                $pickingListOrderItemGroupTransfer->getWarehouseOrFail()->getPickingListStrategyOrFail(),
            ),
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer
     */
    protected function createPickingListOrderItemGroupTransfer(
        array $itemTransferCollection,
        StockTransfer $stockTransfer
    ): PickingListOrderItemGroupTransfer {
        return (new PickingListOrderItemGroupTransfer())
            ->setOrderItems(new ArrayObject($itemTransferCollection))
            ->setWarehouse($stockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $generatedPickingListCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function mergePickingListCollectionTransfer(
        PickingListCollectionTransfer $generatedPickingListCollectionTransfer,
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        foreach ($generatedPickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $pickingListCollectionTransfer->addPickingList($pickingListTransfer);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredOrderItemProperties(GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer): void
    {
        $generatePickingListsRequestTransfer->requireOrderItems();
        foreach ($generatePickingListsRequestTransfer->getOrderItems() as $itemTransfer) {
            $itemTransfer->requireUuid();

            $itemTransfer->requireWarehouse()
                ->getWarehouseOrFail()
                ->requireIdStock()
                ->requirePickingListStrategy();
        }
    }
}

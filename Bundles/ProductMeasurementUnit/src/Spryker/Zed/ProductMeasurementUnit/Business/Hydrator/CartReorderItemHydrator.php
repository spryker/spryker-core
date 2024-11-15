<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface
     */
    protected ProductMeasurementUnitItemExtractorInterface $productMeasurementUnitItemExtractor;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface
     */
    protected ProductMeasurementUnitToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Extractor\ProductMeasurementUnitItemExtractorInterface $productMeasurementUnitItemExtractor
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitItemExtractorInterface $productMeasurementUnitItemExtractor,
        ProductMeasurementUnitToStoreFacadeInterface $storeFacade
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->productMeasurementUnitItemExtractor = $productMeasurementUnitItemExtractor;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithQuantitySalesUnit = $this->productMeasurementUnitItemExtractor->extractItemsWithQuantitySalesUnit(
            $cartReorderTransfer->getOrderItems(),
        );
        if ($itemsWithQuantitySalesUnit === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemTransfersIndexedByIdSalesOrder($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithQuantitySalesUnit as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if ($reorderItemTransfer === null) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $this->addQuantitySalesUnitToReorderItem($itemTransfer, $reorderItemTransfer);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $index
     *
     * @return void
     */
    protected function addReorderItem(
        CartReorderTransfer $cartReorderTransfer,
        ItemTransfer $itemTransfer,
        int $index
    ): void {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail());
        $reorderItemTransfer = $this->addQuantitySalesUnitToReorderItem($itemTransfer, $reorderItemTransfer);

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $reorderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addQuantitySalesUnitToReorderItem(
        ItemTransfer $itemTransfer,
        ItemTransfer $reorderItemTransfer
    ): ItemTransfer {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitRepository
            ->getProductMeasurementSalesUnitTransfersByIdProduct($itemTransfer->getIdOrFail());

        if (count($productMeasurementSalesUnitTransfers) === 1) {
            return $reorderItemTransfer->setQuantitySalesUnit(array_shift($productMeasurementSalesUnitTransfers));
        }

        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            if (!$productMeasurementSalesUnitTransfer->getIsDefault()) {
                continue;
            }

            $reorderItemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);
        }

        return $reorderItemTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrder(ArrayObject $itemTransfers): array
    {
        $indexedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $indexedItemTransfers;
    }
}

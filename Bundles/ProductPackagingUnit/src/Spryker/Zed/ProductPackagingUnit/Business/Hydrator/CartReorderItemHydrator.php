<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Extractor\ProductPackagingUnitItemExtractorInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Extractor\ProductPackagingUnitItemExtractorInterface
     */
    protected ProductPackagingUnitItemExtractorInterface $productPackagingUnitItemExtractor;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    protected ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Extractor\ProductPackagingUnitItemExtractorInterface $productPackagingUnitItemExtractor
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(
        ProductPackagingUnitItemExtractorInterface $productPackagingUnitItemExtractor,
        ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
        $this->productPackagingUnitItemExtractor = $productPackagingUnitItemExtractor;
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithAmountSalesUnit = $this->productPackagingUnitItemExtractor->extractItemsWithAmountSalesUnit(
            $cartReorderTransfer->getOrderItems(),
        );
        if ($itemsWithAmountSalesUnit === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemTransfersIndexedByIdSalesOrderItem($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithAmountSalesUnit as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if ($reorderItemTransfer === null) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $reorderItemTransfer->setAmount($itemTransfer->getAmountOrFail());
            $this->addAmountSalesUnitToReorderItem($itemTransfer, $reorderItemTransfer);
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
    protected function addReorderItem(CartReorderTransfer $cartReorderTransfer, ItemTransfer $itemTransfer, int $index): void
    {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail())
            ->setAmount($itemTransfer->getAmountOrFail());
        $reorderItemTransfer = $this->addAmountSalesUnitToReorderItem($itemTransfer, $reorderItemTransfer);

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $reorderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addAmountSalesUnitToReorderItem(ItemTransfer $itemTransfer, ItemTransfer $reorderItemTransfer): ItemTransfer
    {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct(
            $itemTransfer->getAmountLeadProductOrFail()->getIdProductConcrete(),
        );

        if (count($productMeasurementSalesUnitTransfers) === 1) {
            return $reorderItemTransfer->setAmountSalesUnit(array_shift($productMeasurementSalesUnitTransfers));
        }

        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            if (!$productMeasurementSalesUnitTransfer->getIsDefault()) {
                continue;
            }

            $reorderItemTransfer->setAmountSalesUnit($productMeasurementSalesUnitTransfer);
        }

        return $reorderItemTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrderItem(ArrayObject $itemTransfers): array
    {
        $indexedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $indexedItemTransfers;
    }
}

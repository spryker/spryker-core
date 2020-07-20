<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Order;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface
     */
    protected $productMeasurementUnitTranslationExpander;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation\ProductMeasurementUnitTranslationExpanderInterface $productMeasurementUnitTranslationExpander
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitTranslationExpanderInterface $productMeasurementUnitTranslationExpander
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->productMeasurementUnitTranslationExpander = $productMeasurementUnitTranslationExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer {
        $productMeasurementSalesUnitTransfer = $itemTransfer->getQuantitySalesUnit();

        if (!$productMeasurementSalesUnitTransfer) {
            return $salesOrderItemEntity;
        }

        $productMeasurementSalesUnitTransfer->requireProductMeasurementUnit();
        $quantityMeasurementUnitTransfer = $productMeasurementSalesUnitTransfer->getProductMeasurementUnit();

        $quantityBaseMeasurementUnitName = $productMeasurementSalesUnitTransfer
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $salesOrderItemEntity->setQuantityBaseMeasurementUnitName($quantityBaseMeasurementUnitName);
        $salesOrderItemEntity->setQuantityMeasurementUnitName($quantityMeasurementUnitTransfer->getName());
        $salesOrderItemEntity->setQuantityMeasurementUnitCode($quantityMeasurementUnitTransfer->getCode());

        $salesOrderItemEntity->setQuantityMeasurementUnitPrecision($productMeasurementSalesUnitTransfer->getPrecision());
        $salesOrderItemEntity->setQuantityMeasurementUnitConversion($productMeasurementSalesUnitTransfer->getConversion());

        return $salesOrderItemEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithQuantitySalesUnit(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $mappedProductMeasurementSalesUnits = $this->productMeasurementUnitRepository->getMappedProductMeasurementSalesUnits($salesOrderItemIds);

        $mappedProductMeasurementSalesUnits = $this->productMeasurementUnitTranslationExpander
            ->translateProductMeasurementSalesUnits($mappedProductMeasurementSalesUnits);

        foreach ($itemTransfers as $itemTransfer) {
            $productMeasurementSalesUnitTransfer = $mappedProductMeasurementSalesUnits[$itemTransfer->getIdSalesOrderItem()] ?? null;

            if ($productMeasurementSalesUnitTransfer) {
                $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return array_unique($salesOrderItemIds);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Order;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithQuantitySalesUnit(OrderTransfer $orderTransfer): OrderTransfer
    {
        $spySalesOrderItemEntityTransfers = $this->productMeasurementUnitRepository
            ->querySalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($spySalesOrderItemEntityTransfers as $spySalesOrderItemEntityTransfer) {
            $itemTransfer = $this->findItemTransferByIdSalesOrderItem(
                $orderTransfer,
                $spySalesOrderItemEntityTransfer->getIdSalesOrderItem()
            );

            if ($itemTransfer === null) {
                continue;
            }

            $quantitySalesUnit = $this->hydrateQuantitySalesUnitTransfer($spySalesOrderItemEntityTransfer);
            $itemTransfer->setQuantitySalesUnit($quantitySalesUnit);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function hydrateQuantitySalesUnitTransfer(SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setConversion($spySalesOrderItemEntityTransfer->getQuantityMeasurementUnitConversion());
        $productMeasurementSalesUnitTransfer->setPrecision($spySalesOrderItemEntityTransfer->getQuantityMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($spySalesOrderItemEntityTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntityTransfer->getQuantityMeasurementUnitName());
        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param string|null $productMeasurementUnitName
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function createProductMeasurementUnitTransfer(?string $productMeasurementUnitName = null): ProductMeasurementUnitTransfer
    {
        $productMeasurementUnitTransfer = new ProductMeasurementUnitTransfer();
        $productMeasurementUnitTransfer->setName($productMeasurementUnitName);

        return $productMeasurementUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    protected function createProductMeasurementBaseUnitTransfer(
        SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
    ): ProductMeasurementBaseUnitTransfer {
        $productMeasurementBaseUnitTransfer = new ProductMeasurementBaseUnitTransfer();

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntityTransfer->getQuantityBaseMeasurementUnitName());
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferByIdSalesOrderItem(OrderTransfer $orderTransfer, int $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }
}

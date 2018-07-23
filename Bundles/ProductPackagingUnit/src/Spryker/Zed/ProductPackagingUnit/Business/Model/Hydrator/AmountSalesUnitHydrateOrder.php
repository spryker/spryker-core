<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class AmountSalesUnitHydrateOrder implements AmountSalesUnitHydrateOrderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAmountSalesUnit(OrderTransfer $orderTransfer): OrderTransfer
    {
        $spySalesOrderItemEntityTransfers = $this->productPackagingUnitRepository
            ->findSalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($spySalesOrderItemEntityTransfers as $spySalesOrderItemEntityTransfer) {
            $itemTransfer = $this->findItemTransferAmountSalesUnitsBelongTo(
                $orderTransfer,
                $spySalesOrderItemEntityTransfer->getIdSalesOrderItem()
            );

            if (!$itemTransfer || $spySalesOrderItemEntityTransfer->getAmountMeasurementUnitName() === null) {
                continue;
            }

            $this->setAmountSalesUnit($itemTransfer, $spySalesOrderItemEntityTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
     *
     * @return void
     */
    protected function setAmountSalesUnit(ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer): void
    {
        $itemAmountMeasurementUnitTransfer = $this->hydrateItemAmountSalesUnitTransfer($spySalesOrderItemEntityTransfer);

        $itemTransfer->setAmountSalesUnit($itemAmountMeasurementUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function hydrateItemAmountSalesUnitTransfer(SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setConversion($spySalesOrderItemEntityTransfer->getAmountMeasurementUnitConversion());
        $productMeasurementSalesUnitTransfer->setPrecision($spySalesOrderItemEntityTransfer->getAmountMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($spySalesOrderItemEntityTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntityTransfer->getAmountMeasurementUnitName() ?? '');
        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param string $productMeasurementUnitName
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function createProductMeasurementUnitTransfer(string $productMeasurementUnitName): ProductMeasurementUnitTransfer
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
    protected function createProductMeasurementBaseUnitTransfer(SpySalesOrderItemEntityTransfer $spySalesOrderItemEntityTransfer): ProductMeasurementBaseUnitTransfer
    {
        $productMeasurementBaseUnitTransfer = new ProductMeasurementBaseUnitTransfer();
        $amountBaseMeasurementUnitName = $spySalesOrderItemEntityTransfer->getAmountBaseMeasurementUnitName() ?? '';
        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($amountBaseMeasurementUnitName);
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferAmountSalesUnitsBelongTo(OrderTransfer $orderTransfer, $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }
}

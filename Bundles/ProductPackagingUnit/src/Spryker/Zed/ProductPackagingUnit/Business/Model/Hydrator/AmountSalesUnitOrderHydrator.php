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
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface;

class AmountSalesUnitOrderHydrator implements AmountSalesUnitOrderHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(ProductPackagingUnitToSalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithAmountSalesUnit(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderQuery = $this->salesQueryContainer->querySalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $salesOrderItemEntities = $salesOrderQuery->find();

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $itemTransfer = $this->findItemTransferAmountSalesUnitsBelongTo(
                $orderTransfer,
                $salesOrderItemEntity->getIdSalesOrderItem()
            );

            if ($itemTransfer === null) {
                continue;
            }

            $itemAmountMeasurementUnitTransfer = $this->hydrateItemAmountSalesUnitTransfer($salesOrderItemEntity);

            $itemTransfer->setAmountSalesUnit($itemAmountMeasurementUnitTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $spySalesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function hydrateItemAmountSalesUnitTransfer(SpySalesOrderItem $spySalesOrderItemEntity): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setConversion($spySalesOrderItemEntity->getAmountMeasurementUnitConversion());
        $productMeasurementSalesUnitTransfer->setPrecision($spySalesOrderItemEntity->getAmountMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($spySalesOrderItemEntity);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntity->getAmountMeasurementUnitName());
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $spySalesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function createProductMeasurementBaseUnitTransfer(SpySalesOrderItem $spySalesOrderItemEntity): ProductMeasurementBaseUnitTransfer
    {
        $productMeasurementBaseUnitTransfer = new ProductMeasurementBaseUnitTransfer();
        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntity->getAmountBaseMeasurementUnitName());
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

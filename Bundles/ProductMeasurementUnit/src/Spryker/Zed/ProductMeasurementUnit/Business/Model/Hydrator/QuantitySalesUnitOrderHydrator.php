<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\ProductMeasurementUnit\Dependency\QueryContainer\ProductMeasurementUnitToSalesQueryContainerInterface;

class QuantitySalesUnitOrderHydrator implements QuantitySalesUnitOrderHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\QueryContainer\ProductMeasurementUnitToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\QueryContainer\ProductMeasurementUnitToSalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(ProductMeasurementUnitToSalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithQuantitySalesUnit(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderQuery = $this->salesQueryContainer->querySalesOrderItemsByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $salesOrderItems = $salesOrderQuery->find();

        foreach ($salesOrderItems as $salesOrderItemEntity) {
            $itemTransfer = $this->findItemTransferQuantitySalesUnitsBelongTo(
                $orderTransfer,
                $salesOrderItemEntity->getIdSalesOrderItem()
            );

            if ($itemTransfer === null) {
                continue;
            }

            $quantitySalesUnit = $this->hydrateQuantitySalesUnitTransfer($salesOrderItemEntity);
            $itemTransfer->setQuantitySalesUnit($quantitySalesUnit);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $spySalesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function hydrateQuantitySalesUnitTransfer(SpySalesOrderItem $spySalesOrderItemEntity): ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setConversion($spySalesOrderItemEntity->getQuantityMeasurementUnitConversion());
        $productMeasurementSalesUnitTransfer->setPrecision($spySalesOrderItemEntity->getQuantityMeasurementUnitPrecision());

        $productMeasurementBaseUnitTransfer = $this->createProductMeasurementBaseUnitTransfer($spySalesOrderItemEntity);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntity->getQuantityMeasurementUnitName());
        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param string|null $productMeasurementUnitName
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function createProductMeasurementUnitTransfer(?string $productMeasurementUnitName): ProductMeasurementUnitTransfer
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

        $productMeasurementUnitTransfer = $this->createProductMeasurementUnitTransfer($spySalesOrderItemEntity->getQuantityBaseMeasurementUnitName());
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransferQuantitySalesUnitsBelongTo(OrderTransfer $orderTransfer, $idSalesOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $itemTransfer;
            }
        }

        return null;
    }
}

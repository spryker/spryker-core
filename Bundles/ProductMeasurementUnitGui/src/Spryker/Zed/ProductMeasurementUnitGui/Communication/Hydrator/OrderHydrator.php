<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Communication\Hydrator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

class OrderHydrator implements OrderHydratorInterface
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected $salesOrderItemQuery;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery
     */
    public function __construct(
        SpySalesOrderItemQuery $salesOrderItemQuery
    ) {
        $this->salesOrderItemQuery = $salesOrderItemQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrder(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemEntity = $this->findByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());
            if (!$orderItemEntity ||
                !$orderItemEntity->getQuantityMeasurementUnitName() ||
                !$orderItemEntity->getAmountMeasurementUnitName()) {
                continue;
            }

            $itemTransfer
                ->setAmountSalesUnit($this->createAmountProductMeasurementSalesUnitTransfer($orderItemEntity))
                ->setQuantitySalesUnit($this->createQuantityProductMeasurementSalesUnitTransfer($orderItemEntity));
        }

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    protected function findByIdSalesOrderItem(int $idSalesOrderItem): ?SpySalesOrderItem
    {
        return $this->salesOrderItemQuery->findOneByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function createQuantityProductMeasurementSalesUnitTransfer(SpySalesOrderItem $orderItemEntity): ProductMeasurementSalesUnitTransfer
    {
        return (new ProductMeasurementSalesUnitTransfer())
            ->setConversion($orderItemEntity->getQuantityMeasurementUnitConversion())
            ->setPrecision($orderItemEntity->getQuantityMeasurementUnitPrecision())
            ->setProductMeasurementUnit(
                (new ProductMeasurementUnitTransfer())
                    ->setName($orderItemEntity->getQuantityMeasurementUnitName())
            )
            ->setProductMeasurementBaseUnit(
                (new ProductMeasurementBaseUnitTransfer())
                    ->setProductMeasurementUnit(
                        (new ProductMeasurementUnitTransfer())
                            ->setName($orderItemEntity->getQuantityBaseMeasurementUnitName())
                    )
            );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function createAmountProductMeasurementSalesUnitTransfer(SpySalesOrderItem $orderItemEntity): ProductMeasurementSalesUnitTransfer
    {
        return (new ProductMeasurementSalesUnitTransfer())
            ->setConversion($orderItemEntity->getAmountMeasurementUnitConversion())
            ->setPrecision($orderItemEntity->getAmountMeasurementUnitPrecision())
            ->setProductMeasurementUnit(
                (new ProductMeasurementUnitTransfer())
                    ->setName($orderItemEntity->getAmountMeasurementUnitName())
            )
            ->setProductMeasurementBaseUnit(
                (new ProductMeasurementBaseUnitTransfer())
                    ->setProductMeasurementUnit(
                        (new ProductMeasurementUnitTransfer())
                            ->setName($orderItemEntity->getAmountBaseMeasurementUnitName())
                    )
            );
    }
}

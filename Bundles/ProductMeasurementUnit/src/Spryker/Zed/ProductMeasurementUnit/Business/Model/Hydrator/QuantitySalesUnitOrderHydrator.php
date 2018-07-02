<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemQuantityMeasurementUnitTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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

            $itemQuantityMeasurementUnitTransfer = $this->hydrateItemAmountMeasurementUnitTransfer($salesOrderItemEntity);

            $itemTransfer->setItemQuantityMeasurementUnit($itemQuantityMeasurementUnitTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $spySalesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemQuantityMeasurementUnitTransfer
     */
    protected function hydrateItemAmountMeasurementUnitTransfer(SpySalesOrderItem $spySalesOrderItemEntity): ItemQuantityMeasurementUnitTransfer
    {
        return (new ItemQuantityMeasurementUnitTransfer())->fromArray($spySalesOrderItemEntity->toArray(), true);
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

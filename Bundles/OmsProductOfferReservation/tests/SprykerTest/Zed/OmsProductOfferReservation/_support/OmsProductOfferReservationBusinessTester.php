<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OmsProductOfferReservation;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsProcessTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OmsStateTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\DecimalObject\Decimal;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class OmsProductOfferReservationBusinessTester extends Actor
{
    use _generated\OmsProductOfferReservationBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param int $quantity
     * @param int $itemsCount
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveSalesOrderWithItems(
        ProductOfferTransfer $productOfferTransfer,
        int $quantity,
        int $itemsCount,
        StoreTransfer $storeTransfer
    ): SpySalesOrder {
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            ItemTransfer::QUANTITY => $quantity,
        ]))->build();

        $salesOrderEntity = $this->haveSalesOrderEntity(array_fill(0, $itemsCount, $itemTransfer));
        $salesOrderEntity
            ->setStore($storeTransfer->getName())
            ->save();

        foreach ($salesOrderEntity->getItems() as $orderItemEntity) {
            $orderItemEntity
                ->setSku($productOfferTransfer->getConcreteSku())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setQuantity($quantity)
                ->save();
        }

        return $salesOrderEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function getOmsStateCollectionTransfer(SpySalesOrder $salesOrderEntity): OmsStateCollectionTransfer
    {
        $stateCollectionTransfer = new OmsStateCollectionTransfer();
        foreach ($salesOrderEntity->getItems() as $orderItemEntity) {
            $stateName = $orderItemEntity->getState()->getName();
            $processName = $orderItemEntity->getProcess()->getName();
            $stateCollectionTransfer->addState(
                $stateName,
                (new OmsStateTransfer())
                    ->setName($stateName)
                    ->addProcess($processName, (new OmsProcessTransfer())->setName($processName)),
            );
        }

        return $stateCollectionTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer> $salesAggregationTransfers
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumSalesAggregationTransfers(array $salesAggregationTransfers): Decimal
    {
        return array_reduce($salesAggregationTransfers, function (Decimal $result, SalesOrderItemStateAggregationTransfer $salesAggregationTransfer) {
            return $result->add($salesAggregationTransfer->getSumAmount())->trim();
        }, new Decimal(0));
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferPackagingUnit;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsProcessTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OmsStateTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\DecimalObject\Decimal;

/**
 * Inherited Methods
 *
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
class ProductOfferPackagingUnitBusinessTester extends Actor
{
    use _generated\ProductOfferPackagingUnitBusinessTesterActions;

    /**
     * @param string $productOfferReference
     * @param int $itemsCount
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string|null $sku
     * @param \Spryker\DecimalObject\Decimal|null $amount
     * @param string|null $amountSku
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveSalesOrderWithItems(
        string $productOfferReference,
        int $itemsCount,
        int $quantity,
        StoreTransfer $storeTransfer,
        ?string $sku = null,
        ?Decimal $amount = null,
        ?string $amountSku = null
    ): SpySalesOrder {
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => $sku,
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferReference,
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::AMOUNT => $amount,
        ]))->build();

        $salesOrderEntity = $this->haveSalesOrderEntity(array_fill(0, $itemsCount, $itemTransfer));
        $salesOrderEntity
            ->setStore($storeTransfer->getName())
            ->save();

        foreach ($salesOrderEntity->getItems() as $orderItemEntity) {
            $orderItemEntity
                ->setProductOfferReference($productOfferReference)
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setAmountSku($amountSku)
                ->setAmount($amount)
                ->save();
        }

        return $salesOrderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[] $salesAggregationTransfers
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumSalesAggregationTransfers(array $salesAggregationTransfers): Decimal
    {
        return array_reduce($salesAggregationTransfers, function (Decimal $result, SalesOrderItemStateAggregationTransfer $salesAggregationTransfer) {
            return $result->add($salesAggregationTransfer->getSumAmount())->trim();
        }, new Decimal(0));
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
                    ->addProcess($processName, (new OmsProcessTransfer())->setName($processName))
            );
        }

        return $stateCollectionTransfer;
    }
}

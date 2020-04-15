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
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
     * @param int $quantity
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param int $itemsCount
     * @param bool $selfLead
     *
     * @return array
     */
    public function haveProductOfferPackagingUnitWithSalesOrderItems(int $quantity, Decimal $amount, int $itemsCount, bool $selfLead = false): array
    {
        [$leadProductConcreteTransfer, $packagingUnitProductConcreteTransfer] = $this->havePackagingUnitAndLead($selfLead);

        $packagingUnitProductPackagingUnitType = $this->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => 'packagingUnit']);

        $this->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $leadProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $packagingUnitProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $packagingUnitProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => 1,
        ]);

        $productOfferTransfer = $this->haveProductOffer([ProductOfferTransfer::CONCRETE_SKU => $leadProductConcreteTransfer->getSku()]);

        $stateCollectionTransfer = $this->haveSalesOrderWithItems(
            $productOfferTransfer->getProductOfferReference(),
            $itemsCount,
            $quantity,
            $packagingUnitProductConcreteTransfer->getSku(),
            $amount,
            $leadProductConcreteTransfer->getSku()
        );

        return [
            $stateCollectionTransfer,
            $productOfferTransfer,
        ];
    }

    /**
     * @param string $productOfferReference
     * @param int $itemsCount
     * @param int $quantity
     * @param string|null $sku
     * @param \Spryker\DecimalObject\Decimal|null $amount
     * @param string|null $amountSku
     *
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function haveSalesOrderWithItems(
        string $productOfferReference,
        int $itemsCount,
        int $quantity,
        ?string $sku = null,
        ?Decimal $amount = null,
        ?string $amountSku = null
    ): OmsStateCollectionTransfer {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => $sku,
            ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferReference,
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::AMOUNT => $amount,
        ]))->build();

        $stateCollectionTransfer = new OmsStateCollectionTransfer();
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

    /**
     * @param bool $selfLead
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function havePackagingUnitAndLead(bool $selfLead): array
    {
        $leadProductConcreteTransfer = $this->haveProduct();
        if ($selfLead) {
            return [
                $leadProductConcreteTransfer,
                $leadProductConcreteTransfer,
            ];
        }

        $packagingUnitProductConcreteTransfer = $this->haveProduct();

        return [
            $leadProductConcreteTransfer,
            $packagingUnitProductConcreteTransfer,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[] $salesAggregationTransfers
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function sumSalesAggregationTransfers(array $salesAggregationTransfers): Decimal
    {
        return array_reduce($salesAggregationTransfers, function (Decimal $result, SalesOrderItemStateAggregationTransfer $salesAggregationTransfer) {
            return $result->add($salesAggregationTransfer->getSumAmount())->trim();
        }, new Decimal(0));
    }
}

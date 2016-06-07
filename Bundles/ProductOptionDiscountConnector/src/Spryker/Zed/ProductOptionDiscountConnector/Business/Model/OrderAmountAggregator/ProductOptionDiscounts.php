<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesDiscountTableMap;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class ProductOptionDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $salesOrderDiscounts = $this->getSalesOrderDiscounts($orderTransfer);

        if (count($salesOrderDiscounts) === 0) {
            $this->addProductOptionWithDiscountsGrossPriceAmountDefaults($orderTransfer);

            return;
        }

        $this->populateProductOptionDiscountsFromSalesOrderDiscounts($orderTransfer, $salesOrderDiscounts);
        $this->addProductOptionWithDiscountsGrossPriceAmounts($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesDiscount[] $salesOrderDiscounts
     *
     * @return void
     */
    protected function populateProductOptionDiscountsFromSalesOrderDiscounts(
        OrderTransfer $orderTransfer,
        ObjectCollection $salesOrderDiscounts
    ) {
        foreach ($salesOrderDiscounts as $salesOrderDiscountEntity) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $this->addProductOptionCalculatedDiscounts($itemTransfer, $salesOrderDiscountEntity);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesOrderDiscountEntity
     *
     * @return void
     */
    protected function addProductOptionCalculatedDiscounts(
        ItemTransfer $itemTransfer,
        SpySalesDiscount $salesOrderDiscountEntity
    ) {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if ($salesOrderDiscountEntity->getFkSalesOrderItemOption() !== $productOptionTransfer->getIdSalesOrderItemOption()) {
                continue;
            }

            $calculatedDiscountTransfer = $this->getHydratedCalculatedDiscountTransferFromEntity(
                $salesOrderDiscountEntity,
                $productOptionTransfer->getQuantity()
            );

            $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesOrderDiscountEntity
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function getHydratedCalculatedDiscountTransferFromEntity(SpySalesDiscount $salesOrderDiscountEntity, $quantity)
    {
        $quantity = !empty($quantity) ? $quantity : 1;

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->fromArray($salesOrderDiscountEntity->toArray(), true);
        $calculatedDiscountTransfer->setQuantity($quantity);
        $calculatedDiscountTransfer->setUnitGrossAmount($salesOrderDiscountEntity->getAmount());
        $calculatedDiscountTransfer->setSumGrossAmount($salesOrderDiscountEntity->getAmount() * $quantity);

        foreach ($salesOrderDiscountEntity->getDiscountCodes() as $discountCodeEntity) {
            $calculatedDiscountTransfer->setVoucherCode($discountCodeEntity->getCode());
        }

        return $calculatedDiscountTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountSumGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalSumGrossDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountUnitGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalUnitGrossDiscountAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalUnitGrossDiscountAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
        }

        return $totalUnitGrossDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addProductOptionWithDiscountsGrossPriceAmounts(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalItemUnitDiscountAmount = $this->getCalculatedDiscountUnitGrossAmount($itemTransfer->getCalculatedDiscounts());
            $totalItemSumDiscountAmount = $this->getCalculatedDiscountSumGrossAmount($itemTransfer->getCalculatedDiscounts());

            $productOptionUnitAmount = $this->getProductOptionUnitAmount($itemTransfer);
            $productOptionSumAmount = $this->getProductOptionSumAmount($itemTransfer);

            $unitDiscountAmountWithOptions = $totalItemUnitDiscountAmount + $productOptionUnitAmount;
            $sumDiscountAmountWithOptions = $totalItemSumDiscountAmount + $productOptionSumAmount;

            $itemTransfer->setUnitTotalDiscountAmountWithProductOption($unitDiscountAmountWithOptions);
            $itemTransfer->setSumTotalDiscountAmountWithProductOption($sumDiscountAmountWithOptions);

            $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getUnitGrossPriceWithProductOptions() - $unitDiscountAmountWithOptions
            );
            $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getSumGrossPriceWithProductOptions() - $sumDiscountAmountWithOptions
            );

            $itemTransfer->setRefundableAmount(
                $itemTransfer->getRefundableAmount() - $sumDiscountAmountWithOptions
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addProductOptionWithDiscountsGrossPriceAmountDefaults(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalItemUnitDiscountAmount = $this->getCalculatedDiscountUnitGrossAmount($itemTransfer->getCalculatedDiscounts());
            $totalItemSumDiscountAmount = $this->getCalculatedDiscountSumGrossAmount($itemTransfer->getCalculatedDiscounts());

            $itemTransfer->setUnitTotalDiscountAmountWithProductOption($totalItemUnitDiscountAmount);
            $itemTransfer->setSumTotalDiscountAmountWithProductOption($totalItemSumDiscountAmount);

            $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getUnitGrossPriceWithProductOptions() - $totalItemUnitDiscountAmount
            );

            $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getSumGrossPriceWithProductOptions() - $totalItemSumDiscountAmount
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionUnitAmount(ItemTransfer $itemTransfer)
    {
        $productOptionUnitAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionUnitAmount += $this->getCalculatedDiscountUnitGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );
        }

        return $productOptionUnitAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionSumAmount(ItemTransfer $itemTransfer)
    {
        $productOptionSumAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionSumAmount += $this->getCalculatedDiscountSumGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );
        }

        return $productOptionSumAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[]
     */
    protected function getSaleOrderItemIds(OrderTransfer $orderTransfer)
    {
        $saleOrderItemIds = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $saleOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $saleOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getSalesOrderDiscounts(OrderTransfer $orderTransfer)
    {
        $saleOrderItemIds = $this->getSaleOrderItemIds($orderTransfer);

        if (empty($saleOrderItemIds)) {
            return [];
        }

        return $this->discountQueryContainer
            ->querySalesDisount()
            ->filterByFkSalesOrderItem($saleOrderItemIds)
            ->where(SpySalesDiscountTableMap::COL_FK_SALES_ORDER_ITEM_OPTION . ' IS NOT NULL')
            ->find();
    }

}

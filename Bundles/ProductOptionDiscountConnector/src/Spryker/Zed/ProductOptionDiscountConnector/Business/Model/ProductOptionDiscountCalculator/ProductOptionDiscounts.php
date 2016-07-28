<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Business\Model\ProductOptionDiscountCalculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesDiscountTableMap;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\Calculator\CalculatorInterface;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderAmountAggregatorInterface;

class ProductOptionDiscounts implements OrderAmountAggregatorInterface, CalculatorInterface
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
            $this->addProductOptionWithDiscountsGrossPriceAmountDefaults($orderTransfer->getItems());
            return;
        }

        $this->populateProductOptionDiscountsFromSalesOrderDiscounts($orderTransfer, $salesOrderDiscounts);
        $this->addProductOptionWithDiscountsGrossPriceAmounts($orderTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->setCalculatedProductOptionCalculatedDiscounts($quoteTransfer->getItems());
        $this->addProductOptionWithDiscountsGrossPriceAmounts($quoteTransfer->getItems());
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
                $this->addSalesProductOptionCalculatedDiscounts($itemTransfer, $salesOrderDiscountEntity);
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function setCalculatedProductOptionCalculatedDiscounts(\ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $totalDiscountUnitGrossAmount = $this->getProductOptionGrossUnitTotalAmount($itemTransfer->getProductOptions());
            $totalDiscountSumGrossAmount = $this->getProductOptionGrossSumTotalAmount($itemTransfer->getProductOptions());

            $itemTransfer->setUnitTotalDiscountAmountWithProductOption(
                $itemTransfer->getUnitTotalDiscountAmount() + $totalDiscountUnitGrossAmount
            );
            $itemTransfer->setSumTotalDiscountAmountWithProductOption(
                $itemTransfer->getSumTotalDiscountAmount() + $totalDiscountSumGrossAmount
            );

            $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getSumGrossPriceWithProductOptions() - $itemTransfer->getSumTotalDiscountAmountWithProductOption()
            );

            $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
                $itemTransfer->getUnitGrossPriceWithProductOptions() - $itemTransfer->getUnitTotalDiscountAmountWithProductOption()
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $productOptions
     *
     * @return int
     */
    protected function getProductOptionGrossUnitTotalAmount(\ArrayObject $productOptions)
    {
        $totalDiscountUnitGrossAmount = 0;
        $totalUnitGrossAmount = 0;
        foreach ($productOptions as $productOptionTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($productOptionTransfer->getCalculatedDiscounts());

            $unitDiscountAmount = $this->getCalculatedDiscountUnitGrossAmount($productOptionTransfer->getCalculatedDiscounts());
            $productOptionTransfer->setUnitGrossPriceWithDiscounts($productOptionTransfer->getUnitGrossPrice() - $unitDiscountAmount);
            $totalDiscountUnitGrossAmount += $unitDiscountAmount;
            $totalUnitGrossAmount += $productOptionTransfer->getUnitGrossPrice();
        }

        if ($totalDiscountUnitGrossAmount > $totalUnitGrossAmount) {
            $totalDiscountUnitGrossAmount = $totalUnitGrossAmount;
        }

        return $totalDiscountUnitGrossAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $productOptions
     *
     * @return int
     */
    protected function getProductOptionGrossSumTotalAmount(\ArrayObject $productOptions)
    {
        $totalDiscountSumGrossAmount = 0;
        $totalSumAmount = 0;
        foreach ($productOptions as $productOptionTransfer) {
            $this->setCalculatedDiscountsSumGrossAmount($productOptionTransfer->getCalculatedDiscounts());

            $sumDiscountAmount = $this->getCalculatedDiscountSumGrossAmount($productOptionTransfer->getCalculatedDiscounts());
            $productOptionTransfer->setSumGrossPriceWithDiscounts($productOptionTransfer->getSumGrossPrice() - $sumDiscountAmount);
            $totalDiscountSumGrossAmount += $sumDiscountAmount;
            $totalSumAmount += $productOptionTransfer->getSumGrossPrice();
        }

        if ($totalDiscountSumGrossAmount > $totalSumAmount) {
            $totalDiscountSumGrossAmount = $totalSumAmount;
        }

        return $totalDiscountSumGrossAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountsUnitGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalDiscountUnitGrossAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalDiscountUnitGrossAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
        }

        return $totalDiscountUnitGrossAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return void
     */
    protected function setCalculatedDiscountsSumGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $calculatedDiscountTransfer->setSumGrossAmount(
                $calculatedDiscountTransfer->getUnitGrossAmount() * $calculatedDiscountTransfer->getQuantity()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesOrderDiscountEntity
     *
     * @return void
     */
    protected function addSalesProductOptionCalculatedDiscounts(
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
        $appliedDiscounts = [];
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $idDiscount = $calculatedDiscountTransfer->getIdDiscount();
            if (isset($appliedDiscounts[$idDiscount])) {
                continue;
            }
            $totalUnitGrossDiscountAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
            $appliedDiscounts[$idDiscount] = true;
        }

        return $totalUnitGrossDiscountAmount;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function addProductOptionWithDiscountsGrossPriceAmounts(\ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {

            $totalItemSumDiscountAmount = $itemTransfer->getSumTotalDiscountAmount();
            $productOptionsSumDiscountAmount = $this->getProductOptionSumAmount($itemTransfer);
            $sumDiscountAmountWithOptions = $totalItemSumDiscountAmount + $productOptionsSumDiscountAmount;

            if (!$sumDiscountAmountWithOptions) {
                $this->setItemDiscountDefaults($itemTransfer);
                continue;
            }

            $totalItemUnitDiscountAmount = $itemTransfer->getUnitTotalDiscountAmount();
            $productOptionsUnitDiscountAmount = $this->getProductOptionUnitAmount($itemTransfer);
            $unitDiscountAmountWithOptions = $totalItemUnitDiscountAmount + $productOptionsUnitDiscountAmount;

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
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function addProductOptionWithDiscountsGrossPriceAmountDefaults(\ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $this->setItemDiscountDefaults($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setItemProductOptionDefaults(ItemTransfer $itemTransfer)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setUnitGrossPriceWithDiscounts($productOptionTransfer->getUnitGrossPrice());
            $productOptionTransfer->setSumGrossPriceWithDiscounts($productOptionTransfer->getSumGrossPrice());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionUnitAmount(ItemTransfer $itemTransfer)
    {
        $productOptionUnitTotalDiscountAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionUnitDiscountAmount = $this->getCalculatedDiscountUnitGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );

            if ($productOptionUnitDiscountAmount > $productOptionTransfer->getUnitGrossPrice()) {
                $productOptionUnitDiscountAmount = $productOptionTransfer->getUnitGrossPrice();
            }

            $productOptionTransfer->setUnitGrossPriceWithDiscounts(
                $productOptionTransfer->getUnitGrossPrice() - $productOptionUnitDiscountAmount
            );

            $productOptionUnitTotalDiscountAmount += $productOptionUnitDiscountAmount;
        }

        return $productOptionUnitTotalDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getProductOptionSumAmount(ItemTransfer $itemTransfer)
    {
        $productOptionSumTotalDiscountAmount = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {

            $productOptionSumDiscountAmount = $this->getCalculatedDiscountSumGrossAmount(
                $productOptionTransfer->getCalculatedDiscounts()
            );

            if ($productOptionSumDiscountAmount > $productOptionTransfer->getSumGrossPrice()) {
                $productOptionSumDiscountAmount = $productOptionTransfer->getSumGrossPrice();
            }

            $productOptionTransfer->setSumGrossPriceWithDiscounts(
                $productOptionTransfer->getSumGrossPrice() - $productOptionSumDiscountAmount
            );

            $productOptionSumTotalDiscountAmount += $productOptionSumDiscountAmount;
        }

        return $productOptionSumTotalDiscountAmount;
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
            ->querySalesDiscount()
            ->filterByFkSalesOrderItem($saleOrderItemIds, Criteria::IN)
            ->where(SpySalesDiscountTableMap::COL_FK_SALES_ORDER_ITEM_OPTION . Criteria::ISNOTNULL)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setItemDiscountDefaults(ItemTransfer $itemTransfer)
    {
        $this->setItemProductOptionDefaults($itemTransfer);

        $itemTransfer->setUnitTotalDiscountAmountWithProductOption($itemTransfer->getUnitTotalDiscountAmount());
        $itemTransfer->setSumTotalDiscountAmountWithProductOption($itemTransfer->getSumTotalDiscountAmount());

        $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(
            $itemTransfer->getUnitGrossPriceWithProductOptions() - $itemTransfer->getUnitTotalDiscountAmount()
        );

        $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(
            $itemTransfer->getSumGrossPriceWithProductOptions() - $itemTransfer->getSumTotalDiscountAmount()
        );
    }

}

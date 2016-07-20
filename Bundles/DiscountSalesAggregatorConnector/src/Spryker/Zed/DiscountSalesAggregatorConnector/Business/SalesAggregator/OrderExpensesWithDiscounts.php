<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesDiscountTableMap;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class OrderExpensesWithDiscounts implements OrderAmountAggregatorInterface
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
            $this->setExpenseGrossPriceWithDiscountsToDefaults($orderTransfer);

            return;
        }

        $this->addDiscountsFromSalesOrderDiscountEntity($orderTransfer, $salesOrderDiscounts);
        $this->updateExpenseGrossPriceWithDiscounts($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getSalesOrderDiscounts(OrderTransfer $orderTransfer)
    {
        return $this->discountQueryContainer
            ->querySalesDiscount()
            ->where(SpySalesDiscountTableMap::COL_FK_SALES_EXPENSE . ' IS NOT NULL')
            ->findByFkSalesOrder($orderTransfer->getIdSalesOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesDiscount[] $salesOrderDiscounts
     *
     * @return void
     */
    protected function addDiscountsFromSalesOrderDiscountEntity(
        OrderTransfer $orderTransfer,
        ObjectCollection $salesOrderDiscounts
    ) {
        foreach ($salesOrderDiscounts as $salesOrderDiscountEntity) {
            $this->addOrderExpenseCalculatedDiscounts($orderTransfer, $salesOrderDiscountEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesOrderDiscountEntity
     *
     * @return void
     */
    protected function addOrderExpenseCalculatedDiscounts(
        OrderTransfer $orderTransfer,
        SpySalesDiscount $salesOrderDiscountEntity
    ) {
        if ($salesOrderDiscountEntity->getFkSalesExpense() === null) {
            return;
        }

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getIdSalesExpense() !== $salesOrderDiscountEntity->getFkSalesExpense()) {
                continue;
            }

            $calculatedDiscountTransfer = $this->hydrateCalculatedDiscountTransferFromEntity(
                $salesOrderDiscountEntity,
                $expenseTransfer->getQuantity()
            );

            $expenseTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

            $expenseUnitTotalDiscountAmount = $expenseTransfer->getUnitTotalDiscountAmount() + $calculatedDiscountTransfer->getUnitGrossAmount();
            if ($expenseUnitTotalDiscountAmount > $expenseTransfer->getUnitGrossPrice()) {
                $expenseUnitTotalDiscountAmount = $expenseTransfer->getUnitGrossPrice();
            }
            $expenseTransfer->setUnitTotalDiscountAmount($expenseUnitTotalDiscountAmount);

            $expenseSumTotalDiscountAmount = $expenseTransfer->getSumTotalDiscountAmount() + $calculatedDiscountTransfer->getSumGrossAmount();
            if ($expenseSumTotalDiscountAmount > $expenseTransfer->getSumGrossPrice()) {
                $expenseSumTotalDiscountAmount = $expenseTransfer->getSumGrossPrice();
            }
            $expenseTransfer->setSumTotalDiscountAmount($expenseSumTotalDiscountAmount);

            $this->setExpenseRefundableAmount($expenseTransfer, $calculatedDiscountTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function setExpenseGrossPriceWithDiscountsToDefaults($orderTransfer)
    {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->setSumGrossPriceWithDiscounts($expenseTransfer->getSumGrossPrice());
            $expenseTransfer->setUnitGrossPriceWithDiscounts($expenseTransfer->getUnitGrossPrice());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function setExpenseRefundableAmount(
        ExpenseTransfer $expenseTransfer,
        CalculatedDiscountTransfer $calculatedDiscountTransfer
    ) {
        $expenseTransfer->setRefundableAmount(
            $expenseTransfer->getRefundableAmount() - $calculatedDiscountTransfer->getSumGrossAmount()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function updateExpenseGrossPriceWithDiscounts(OrderTransfer $orderTransfer) {

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $expenseTransfer->setUnitGrossPriceWithDiscounts(
                $expenseTransfer->getUnitGrossPrice() - $expenseTransfer->getUnitTotalDiscountAmount()
            );

            $expenseTransfer->setSumGrossPriceWithDiscounts(
                $expenseTransfer->getSumGrossPrice() - $expenseTransfer->getSumTotalDiscountAmount()
            );
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesDiscount $salesOrderDiscountEntity
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function hydrateCalculatedDiscountTransferFromEntity(SpySalesDiscount $salesOrderDiscountEntity, $quantity)
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

}

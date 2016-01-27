<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class ItemDiscounts
{
    /**
     * @var DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * ItemDiscountAmounts constructor.
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer)
    {
        $this->discountQueryContainer = $discountQueryContainer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertItemDiscountsRequirements($orderTransfer);

        $salesOrderDiscounts = $this->getSalesOrderDiscounts($orderTransfer);

        foreach ($salesOrderDiscounts as $salesOrderDiscountEntity) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $this->assertItemRequirements($itemTransfer);
                $this->addItemCalculatedDiscounts($itemTransfer, $salesOrderDiscountEntity);
            }
            $this->addOrderExpenseCalculatedDiscounts($orderTransfer, $salesOrderDiscountEntity);
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     * @param SpySalesDiscount $salesOrderDiscountEntity
     *
     * @return void
     */
    protected function addItemCalculatedDiscounts(
        ItemTransfer $itemTransfer,
        SpySalesDiscount $salesOrderDiscountEntity
    ) {
        if ($itemTransfer->getIdSalesOrderItem() !== $salesOrderDiscountEntity->getFkSalesOrderItem() ||
            $salesOrderDiscountEntity->getFkSalesOrderItemOption() !== null
        ) {
            return;
        }

        $calculatedDiscountTransfer = $this->hydrateCalculatedDiscountTransferFromEntity(
            $salesOrderDiscountEntity,
            $itemTransfer->getQuantity()
        );
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
    }



    /**
     * @param OrderTransfer $orderTransfer
     * @param SpySalesDiscount $salesOrderDiscountEntity
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

            $this->addExpenseDiscountAmount($expenseTransfer, $calculatedDiscountTransfer);
        }
    }

    /**
     * @param SpySalesDiscount $salesOrderDiscountEntity
     * @param int $quantity
     *
     * @return CalculatedDiscountTransfer
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


    /**
     * @param ExpenseTransfer $expenseTransfer
     * @param CalculatedDiscountTransfer $calculatedDiscountTransfer
     *
     * @return void
     */
    protected function addExpenseDiscountAmount(
        ExpenseTransfer $expenseTransfer,
        CalculatedDiscountTransfer $calculatedDiscountTransfer
    ) {
        $expenseTransfer->setUnitGrossPriceWithDiscounts(
            $expenseTransfer->getUnitGrossPrice() - $calculatedDiscountTransfer->getUnitGrossAmount()
        );

        $expenseTransfer->setSumGrossPriceWithDiscounts(
            $expenseTransfer->getSumGrossPriceWithDiscounts() - $calculatedDiscountTransfer->getSumGrossAmount()
        );
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return SpySalesDiscount[]|ObjectCollection
     */
    protected function getSalesOrderDiscounts(OrderTransfer $orderTransfer)
    {
        return $this->discountQueryContainer
            ->querySalesDisount()
            ->findByFkSalesOrder($orderTransfer->getIdSalesOrder());
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertItemDiscountsRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireQuantity()->requireIdSalesOrderItem();
    }
}

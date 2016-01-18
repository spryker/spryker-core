<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
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
        $salesOrderDiscounts = $this->getSalesOrderDiscounts($orderTransfer);

        foreach ($salesOrderDiscounts as $salesOrderDiscountEntity) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $this->addItemCalculatedDiscounts($itemTransfer, $salesOrderDiscountEntity);
                $this->addProductOptionCalculatedDiscounts($itemTransfer, $salesOrderDiscountEntity);
                $this->addOrderExpenseCalculatedDiscounts($orderTransfer, $salesOrderDiscountEntity);
            }
        }
    }

    /**
     * @param ItemTransfer $itemTransfer
     * @param SpySalesDiscount $salesOrderDiscountEntity
     *
     * @return null
     */
    protected function addItemCalculatedDiscounts(
        ItemTransfer $itemTransfer,
        SpySalesDiscount $salesOrderDiscountEntity
    ) {
        if ($itemTransfer->getIdSalesOrderItem() !== $salesOrderDiscountEntity->getFkSalesOrderItem() &&
            $salesOrderDiscountEntity->getFkSalesOrderItemOption() !== null
        ) {
            return;
        }

        $calculatedDiscountTransfer = $this->getCalculatedDiscountTransferFromEntity(
            $salesOrderDiscountEntity,
            $itemTransfer->getQuantity()
        );
        $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
    }

    /**
     * @param ItemTransfer $itemTransfer
     * @param SpySalesDiscount $salesOrderDiscountEntity
     *
     * @return null
     */
    protected function addProductOptionCalculatedDiscounts(
        ItemTransfer $itemTransfer,
        SpySalesDiscount $salesOrderDiscountEntity
    ) {
        if ($itemTransfer->getIdSalesOrderItem() !== $salesOrderDiscountEntity->getFkSalesOrderItem() &&
            $salesOrderDiscountEntity->getFkSalesOrderItemOption() === null) {
            return;
        }

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if ($salesOrderDiscountEntity->getFkSalesOrderItemOption() !== $productOptionTransfer->getIdSalesOrderItemOption()) {
                continue;
            }

            $calculatedDiscountTransfer = $this->getCalculatedDiscountTransferFromEntity(
                $salesOrderDiscountEntity,
                $productOptionTransfer->getQuantity()
            );

            $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        }
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param SpySalesDiscount $salesOrderDiscountEntity
     *
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

            $calculatedDiscountTransfer = $this->getCalculatedDiscountTransferFromEntity(
                $salesOrderDiscountEntity,
                $expenseTransfer->getQuantity()
            );
            $expenseTransfer->addCalculatedDiscount($calculatedDiscountTransfer);
        }
    }

    /**
     * @param SpySalesDiscount $salesOrderDiscountEntity
     * @param int $quantity
     *
     * @return CalculatedDiscountTransfer
     */
    protected function getCalculatedDiscountTransferFromEntity(
        SpySalesDiscount $salesOrderDiscountEntity,
        $quantity
    ) {
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

}

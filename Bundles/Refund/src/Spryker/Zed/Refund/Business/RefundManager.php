<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class RefundManager
{

    /**
     * @var \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface
     */
    protected $refundQueryContainer;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface $refundQueryContainer
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(RefundQueryContainerInterface $refundQueryContainer, SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->refundQueryContainer = $refundQueryContainer;
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculateRefundableAmount(OrderTransfer $orderTransfer)
    {
        $sum = 0;

        $orderItems = $this->getRefundableItems($orderTransfer->getIdSalesOrder());
        foreach ($orderItems as $orderItem) {
            $sum += $orderItem->getPriceToPay() * $orderItem->getQuantity();
        }

        $expenses = $this->getRefundableExpenses($orderTransfer->getIdSalesOrder());
        foreach ($expenses as $expense) {
            $sum += $expense->getPriceToPay();
        }

        $orderGrandTotal = (int) $orderTransfer->getGrandTotal();

        $refunds = $this->getRefunds($orderTransfer->getIdSalesOrder());
        foreach ($refunds as $refund) {
            $orderGrandTotal -= $refund->getAmount();
        }

        if ($sum > $orderGrandTotal) {
            return $orderGrandTotal;
        }

        return $sum;
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getRefundableItems($idOrder)
    {
        $orderItems = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->filterByFkSalesOrder($idOrder)
            ->filterByFkRefund(null, Criteria::ISNULL)
            ->find();

        return $orderItems;
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense[]
     */
    public function getRefundableExpenses($idOrder)
    {
        $expenses = $this->salesQueryContainer
            ->querySalesExpense()
            ->filterByFkSalesOrder($idOrder)
            ->filterByFkRefund(null, Criteria::ISNULL)
            ->find();

        return $expenses;
    }

    /**
     * @param int $idOrder
     *
     * @return \Orm\Zed\Refund\Persistence\SpyRefund[]
     */
    public function getRefunds($idOrder)
    {
        return $this->refundQueryContainer
            ->queryRefundsByIdSalesOrder($idOrder)
            ->find();
    }

}

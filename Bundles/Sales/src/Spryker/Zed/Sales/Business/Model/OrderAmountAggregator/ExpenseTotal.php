<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class ExpenseTotal implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * ExpenseTotal constructor.
     */
    public function __construct(SalesQueryContainerInterface $salesQueryContainer)
    {
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $salesOrderExpenses = $this->getOrderExpenses($orderTransfer);

        if (empty($salesOrderExpenses)) {
            return;
        }

        $orderTotalsTransfer = $orderTransfer->getTotals();
        if ($orderTotalsTransfer === null) {
            $orderTotalsTransfer = new TotalsTransfer();
        }

        $orderTransfer = $this->hydrateOrderExpenseTransfer($salesOrderExpenses, $orderTransfer);
        $totalExpenseAmount = $this->sumTotalExpenseAmount($salesOrderExpenses);
        $orderTotalsTransfer->setExpenseTotal($totalExpenseAmount);

        $orderTransfer->setTotals($orderTotalsTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderExpenses
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateOrderExpenseTransfer(ObjectCollection $salesOrderExpenses, OrderTransfer $orderTransfer)
    {
        $orderTransfer->setExpenses(new \ArrayObject());
        foreach ($salesOrderExpenses as $salesOrderExpenseEntity) {
            $orderExpenseTransfer = new ExpenseTransfer();
            $orderExpenseTransfer->fromArray($salesOrderExpenseEntity->toArray(), true);
            $orderExpenseTransfer->setUnitGrossPrice($salesOrderExpenseEntity->getGrossPrice());
            $orderExpenseTransfer->setSumGrossPrice($salesOrderExpenseEntity->getGrossPrice());
            $orderExpenseTransfer->setRefundableAmount(
                $salesOrderExpenseEntity->getGrossPrice() - $salesOrderExpenseEntity->getCanceledAmount()
            );
            $orderExpenseTransfer->setQuantity(1);
            $orderTransfer->addExpense($orderExpenseTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderExpenses
     *
     * @return int
     */
    protected function sumTotalExpenseAmount(ObjectCollection $salesOrderExpenses)
    {
        $totalExpenseAmount = 0;
        foreach ($salesOrderExpenses as $salesOrderExpenseEntity) {
            $totalExpenseAmount += $salesOrderExpenseEntity->getGrossPrice();
        }

        return $totalExpenseAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getOrderExpenses(OrderTransfer $orderTransfer)
    {
        $salesOrderExpenses = $this->salesQueryContainer->querySalesExpense()
            ->findByFkSalesOrder($orderTransfer->getIdSalesOrder());

        return $salesOrderExpenses;
    }

}

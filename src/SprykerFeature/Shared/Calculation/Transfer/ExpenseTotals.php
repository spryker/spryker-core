<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseTotalItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseTotalItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseTotalsInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class ExpenseTotals extends AbstractTransfer implements ExpenseTotalsInterface
{

    /**
     * @var int
     */
    protected $totalOrderAmount = 0;

    /**
     * @var int
     */
    protected $totalItemAmount = 0;

    /**
     * @var int
     */
    protected $totalAmount = 0;

    /**
     * @var ExpenseTotalItemInterface[]|ExpenseTotalItemCollection
     */
    protected $expenseItems = 'Calculation\\ExpenseTotalItemCollection';

    /**
     * @param int $totalOrderAmount
     *
     * @return $this
     */
    public function setTotalOrderAmount($totalOrderAmount)
    {
        $this->totalOrderAmount = $totalOrderAmount;
        $this->addModifiedProperty('totalOrderAmount');

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalOrderAmount()
    {
        return $this->totalOrderAmount;
    }

    /**
     * @param int $totalItemAmount
     *
     * @return $this
     */
    public function setTotalItemAmount($totalItemAmount)
    {
        $this->totalItemAmount = $totalItemAmount;
        $this->addModifiedProperty('totalItemAmount');

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalItemAmount()
    {
        return $this->totalItemAmount;
    }

    /**
     * @param int $totalAmount
     * @return $this
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        $this->addModifiedProperty('totalAmount');

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param ExpenseTotalItemCollection $expenseItems
     * @return $this
     */
    public function setExpenseItems(ExpenseTotalItemCollection $expenseItems)
    {
        $this->expenseItems = $expenseItems;
        $this->addModifiedProperty('expenseItems');

        return $this;
    }

    /**
     * @return ExpenseTotalItemInterface[]|ExpenseTotalItemCollectionInterface
     */
    public function getExpenseItems()
    {
        return $this->expenseItems;
    }

    /**
     * @param ExpenseTotalItemInterface $expenseItem
     *
     * @return $this
     */
    public function addExpenseItem(ExpenseTotalItemInterface $expenseItem)
    {
        $this->expenseItems->add($expenseItem);
        $this->addModifiedProperty('expenseItems');

        return $this;
    }

    /**
     * @param ExpenseTotalItemInterface $expenseItem
     *
     * @return $this
     */
    public function removeExpenseItem(ExpenseTotalItemInterface $expenseItem)
    {
        $this->expenseItems->remove($expenseItem);
        $this->addModifiedProperty('expenseItems');

        return $this;
    }
}

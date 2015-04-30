<?php

namespace SprykerFeature\Shared\Cart\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Cart extends AbstractTransfer implements CartInterface
{
    /**
     * @var CalculableItemCollectionInterface|CalculableItemInterface[]
     */
    protected $items = 'Cart\\ItemCollection';

    /**
     * @var TotalsInterface
     */
    protected $totals = 'Calculation\\Totals';

    /**
     * @var ExpenseItemCollectionInterface
     */
    protected $expenses = 'Calculation\\ExpenseCollection';

    /**
     * @var DiscountableItemCollectionInterface|DiscountItemInterface[]
     */
    protected $discounts = 'Calculation\\DiscountCollection';

    /**
     * @var array|string
     */
    protected $couponCodes = [];

    /**
     * @return ItemCollectionInterface
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param CalculableItemCollectionInterface $items
     *
     * @return $this
     */
    public function setItems(CalculableItemCollectionInterface $items)
    {
        $this->items = $items;
        $this->addModifiedProperty('items');

        return $this;
    }

    /**
     * @return TotalsInterface
     */
    public function getTotals()
    {
        return $this->totals;
    }

    /**
     * @param TotalsInterface $totals
     *
     * @return $this
     */
    public function setTotals(TotalsInterface $totals)
    {
        $this->totals = $totals;
        $this->addModifiedProperty('totals');

        return $this;
    }

    /**
     * @return ExpenseItemCollectionInterface|ExpenseItemInterface[]
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @param ExpenseItemCollectionInterface $expenses
     *
     * @return $this
     */
    public function setExpenses(ExpenseItemCollectionInterface $expenses)
    {
        $this->expenses = $expenses;
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */
    public function addExpense(ExpenseItemInterface $expenseItem)
    {
        $this->expenses->add($expenseItem);
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @return DiscountItemInterface[]
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * @param DiscountableItemCollectionInterface $collection
     *
     * @return $this
     */
    public function setDiscounts(DiscountableItemCollectionInterface $collection)
    {
        $this->discounts = $collection;
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @return string[]|array
     */
    public function getCouponCodes()
    {
        return $this->couponCodes;
    }

    /**
     * @param string $couponCode
     *
     * @return $this
     */
    public function addCouponCode($couponCode)
    {
        $this->couponCodes[] = $couponCode;
        $this->addModifiedProperty('couponCodes');

        return $this;
    }

    /**
     * @param array|string[] $couponCodes
     *
     * @return $this
     */
    public function setCouponCodes(array $couponCodes)
    {
        $this->couponCodes = $couponCodes;
        $this->addModifiedProperty('couponCodes');

        return $this;
    }

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountItemInterface $discount)
    {
        $this->discounts->add($discount);
        $this->addModifiedProperty('discount');

        return $this;
    }

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function removeDiscount(DiscountItemInterface $discount)
    {
        $this->discounts->remove($discount);
        $this->addModifiedProperty('discount');

        return $this;
    }

    /**
     * @param ExpenseItemInterface $expenseItem
     *
     * @return $this
     */
    public function removeExpense(ExpenseItemInterface $expenseItem)
    {
        $this->expenses->remove($expenseItem);
        $this->addModifiedProperty('expenses');

        return $this;
    }
}

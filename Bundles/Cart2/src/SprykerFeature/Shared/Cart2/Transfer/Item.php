<?php

namespace SprykerFeature\Shared\Cart2\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyExpenseItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyExpenseItemInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyOptionItemInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Item extends AbstractTransfer implements ItemInterface
{
    /**
     * @var int
     */
    private $quantity = 1;

    /**
     * @var string
     */
    private $id;

    /**
     * @var ExpenseItemCollectionInterface|ExpenseItemInterface[]
     */
    private $expenses = 'Calculation\\ExpenseCollection';

    /**
     * @var OptionItemInterface[]
     */
    private $options = [];

    /**
     * @var int
     */
    private $grossPrice = 0;

    /**
     * @var int
     */
    private $priceToPay = 0;

    /**
     * @var int
     */
    private $taxPercentage = 0;

    /**
     * @var DiscountItemInterface[]|DiscountableItemCollectionInterface
     */
    private $discounts = 'Calculation\\DiscountCollection';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setId($identifier)
    {
        $this->id = $identifier;
        $this->addModifiedProperty('id');

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity = 1)
    {
        $this->quantity = $quantity;

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
     * @return OptionItemInterface[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
    }

    /**
     * @param int $grossPrice
     *
     * @return $this
     */
    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = (int) $grossPrice;
        $this->addModifiedProperty('grossPrice');

        return $this;
    }

    /**
     * @param int $priceToPay
     *
     * @return $this
     */
    public function setPriceToPay($priceToPay)
    {
        $this->priceToPay = $priceToPay;
        $this->addModifiedProperty('priceToPay');

        return $this;
    }

    /**
     * @return int
     */
    public function getTaxPercentage()
    {
        return $this->taxPercentage;
    }

    /**
     * @return int
     */
    public function getPriceToPay()
    {
        return $this->priceToPay;
    }

    /**
     * @return DiscountItemInterface[]|DiscountableItemCollectionInterface
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

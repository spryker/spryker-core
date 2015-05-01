<?php

namespace SprykerFeature\Shared\Sales\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemInterface;
use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerFeature\Shared\Calculation\Transfer\DiscountCollection;
use SprykerFeature\Shared\Calculation\Transfer\ExpenseCollection;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\Calculation\Transfer\Expense;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemInterface;

class OrderItem extends AbstractTransfer implements DiscountableItemInterface
{

    protected $idSalesOrderItem = null;

    protected $fkSalesOrder = null;

    protected $fkOmsOrderItemStatus = null;

    protected $name = null;

    protected $sku = null;

    protected $uniqueIdentifier = null;

    protected $grossPrice = null;

    protected $priceToPay = null;

    protected $taxPercentage = null;

    protected $quantity = 1;

    /**
     * @var DiscountableItemCollectionInterface
     */
    protected $discounts = 'Calculation\\DiscountCollection';

    /**
     * @var OrderItemOptionCollection
     */
    protected $options = 'Sales\\OrderItemOptionCollection';

    /**
     * @var ExpenseItemCollectionInterface
     */
    protected $expenses = 'Calculation\\ExpenseCollection';

    protected $unitGrossPrice = null;

    protected $unitPriceToPay = null;

    protected $variety = null;

    protected $status = 'Sales\\OrderItemStatus';

    /**
     * @param int $idSalesOrderItem
     *
     * @return $this
     */
    public function setIdSalesOrderItem($idSalesOrderItem)
    {
        $this->idSalesOrderItem = $idSalesOrderItem;
        $this->addModifiedProperty('idSalesOrderItem');

        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesOrderItem()
    {
        return $this->idSalesOrderItem;
    }

    /**
     * @param int $fkSalesOrder
     *
     * @return $this
     */
    public function setFkSalesOrder($fkSalesOrder)
    {
        $this->fkSalesOrder = $fkSalesOrder;
        $this->addModifiedProperty('fkSalesOrder');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkSalesOrder()
    {
        return $this->fkSalesOrder;
    }

    /**
     * @param int $fkOmsOrderItemStatus
     *
     * @return $this
     */
    public function setFkOmsOrderItemStatus($fkOmsOrderItemStatus)
    {
        $this->fkOmsOrderItemStatus = $fkOmsOrderItemStatus;
        $this->addModifiedProperty('fkOmsOrderItemStatus');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkOmsOrderItemStatus()
    {
        return $this->fkOmsOrderItemStatus;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        $this->addModifiedProperty('sku');

        return $this;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $uniqueIdentifier
     *
     * @return $this
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;
        $this->addModifiedProperty('uniqueIdentifier');

        return $this;
    }

    /**
     * @return string
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * @param int $grossPrice
     *
     * @return $this
     */
    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = $grossPrice;
        $this->addModifiedProperty('grossPrice');

        return $this;
    }

    /**
     * @return int
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
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
    public function getPriceToPay()
    {
        return $this->priceToPay;
    }

    /**
     * @param int $taxPercentage
     *
     * @return $this
     */
    public function setTaxPercentage($taxPercentage)
    {
        $this->taxPercentage = $taxPercentage;
        $this->addModifiedProperty('taxPercentage');

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
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->addModifiedProperty('quantity');

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
     * @param DiscountableItemCollectionInterface $discounts
     *
     * @return $this
     */
    public function setDiscounts(DiscountableItemCollectionInterface $discounts)
    {
        $this->discounts = $discounts;
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @return Discount[]|DiscountCollection
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * @param Discount $discount
     *
     * @return $this
     */
    public function addDiscount(Discount $discount)
    {
        $this->discounts->add($discount);
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @param Discount $discount
     *
     * @return $this
     */
    public function removeDiscount(Discount $discount)
    {
        $this->discounts->remove($discount);
        $this->addModifiedProperty('discounts');

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        $this->addModifiedProperty('options');

        return $this;
    }

    /**
     * @return OrderItemOption[]|OrderItemOptionCollection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param OrderItemOption $option
     *
     * @return $this
     */
    public function addOption(OrderItemOption $option)
    {
        $this->options->add($option);
        $this->addModifiedProperty('options');

        return $this;
    }

    /**
     * @param OrderItemOption $option
     *
     * @return $this
     */
    public function removeOption(OrderItemOption $option)
    {
        $this->options->remove($option);
        $this->addModifiedProperty('options');

        return $this;
    }

    /**
     * @param ExpenseItemCollectionInterface|ExpenseCollection $expenses
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
     * @return Expense[]|ExpenseCollection
     */
    public function getExpenses()
    {
        return $this->expenses;
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
     * @param ExpenseItemInterface $expense
     *
     * @return $this
     */
    public function removeExpense(ExpenseItemInterface $expense)
    {
        $this->expenses->remove($expense);
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @param int $unitGrossPrice
     *
     * @return $this
     */
    public function setUnitGrossPrice($unitGrossPrice)
    {
        $this->unitGrossPrice = $unitGrossPrice;
        $this->addModifiedProperty('unitGrossPrice');

        return $this;
    }

    /**
     * @return int
     */
    public function getUnitGrossPrice()
    {
        return $this->unitGrossPrice;
    }

    /**
     * @param int $unitPriceToPay
     *
     * @return $this
     */
    public function setUnitPriceToPay($unitPriceToPay)
    {
        $this->unitPriceToPay = $unitPriceToPay;
        $this->addModifiedProperty('unitPriceToPay');

        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPriceToPay()
    {
        return $this->unitPriceToPay;
    }

    /**
     * @param string $variety
     *
     * @return $this
     */
    public function setVariety($variety)
    {
        $this->variety = $variety;
        $this->addModifiedProperty('variety');

        return $this;
    }

    /**
     * @return string
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * @param OrderItemStatus $status
     *
     * @return $this
     */
    public function setStatus(OrderItemStatus $status)
    {
        $this->status = $status;
        $this->addModifiedProperty('status');

        return $this;
    }

    /**
     * @return OrderItemStatus
     */
    public function getStatus()
    {
        return $this->status;
    }
}

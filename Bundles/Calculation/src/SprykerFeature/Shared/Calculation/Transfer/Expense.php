<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Discount\DependencyDiscountableExpenseInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use Generated\Shared\Transfer\Calculation\DependencyExpenseItemInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;

class Expense extends AbstractTransfer implements ExpenseItemInterface, DiscountableExpenseInterface
{

    protected $type = null;

    protected $grossPrice = null;

    protected $priceToPay = null;

    protected $taxPercentage = null;

    protected $name = null;

    /**
     * @var DiscountableItemCollectionInterface
     */
    protected $discounts = 'Calculation\\DiscountCollection';

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->addModifiedProperty('type');
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @param string $name
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
     * @return DiscountItemInterface[]|DiscountableItemCollectionInterface
     */
    public function getDiscounts()
    {
        return $this->discounts;
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
}

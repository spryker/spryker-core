<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\DiscountableInterface;
use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerFeature\Shared\Calculation\Transfer\DiscountCollection;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\OptionItemInterface;

class OrderItemOption extends AbstractTransfer implements OptionItemInterface, DiscountableInterface
{

    protected $identifier = null;

    protected $name = null;

    protected $description = null;

    protected $grossPrice = 0;

    protected $priceToPay = 0;

    protected $taxPercentage = 0.0;

    protected $type = null;

    /**
     * @var DiscountableItemCollectionInterface
     */
    protected $discounts = 'Calculation\\DiscountCollection';

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        $this->addModifiedProperty('identifier');

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->addModifiedProperty('description');

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $type
     *
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
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountItemInterface $discount)
    {
        $this->discounts->add($discount);
        $this->addModifiedProperty('discounts');

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
        $this->addModifiedProperty('discounts');

        return $this;
    }
}

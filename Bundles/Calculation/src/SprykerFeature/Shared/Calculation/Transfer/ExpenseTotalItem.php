<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyExpenseTotalItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class ExpenseTotalItem extends AbstractTransfer implements ExpenseTotalItemInterface
{

    protected $name = '';

    protected $type = null;

    protected $grossPrice = 0;

    protected $priceToPay = 0;

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
}

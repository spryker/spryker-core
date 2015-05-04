<?php

namespace SprykerFeature\Shared\Discount\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Generated\Shared\Transfer\Discount\DependencyDiscountItemInterfaceTransfer;

class Discount extends AbstractTransfer implements
    DiscountItemInterface
{

    /**
     * @var int
     */
    protected $idDiscount = null;

    /**
     * @var int
     */
    protected $fkDiscountVoucherPool = null;

    /**
     * @var string
     */
    protected $displayName = null;

    /**
     * @var string
     */
    protected $description = null;

    /**
     * @var int
     */
    protected $amount = null;

    /**
     * @var null|CalculatorPluginInterface
     */
    protected $calculatorPlugin = null;

    /**
     * @var null|DiscountCollectorPluginInterface
     */
    protected $collectorPlugin = null;

    /**
     * @var bool
     */
    protected $isPrivileged = null;

    /**
     * @var bool
     */
    protected $isActive = null;

    /**
     * @var \DateTime
     */
    protected $validFrom = null;

    /**
     * @var \DateTime
     */
    protected $validTo = null;

    /**
     * @param $amount
     * @return Discount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->addModifiedProperty('amount');

        return $this;
    }

    /**
     * @return null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $calculatorPlugin
     * @return Discount
     */
    public function setCalculatorPlugin($calculatorPlugin)
    {
        $this->calculatorPlugin = $calculatorPlugin;
        $this->addModifiedProperty('calculatorPlugin');

        return $this;
    }

    /**
     * @return null
     */
    public function getCalculatorPlugin()
    {
        return $this->calculatorPlugin;
    }

    /**
     * @param $collectorPlugin
     * @return Discount
     */
    public function setCollectorPlugin($collectorPlugin)
    {
        $this->collectorPlugin = $collectorPlugin;
        $this->addModifiedProperty('collectorPlugin');

        return $this;
    }

    /**
     * @return null
     */
    public function getCollectorPlugin()
    {
        return $this->collectorPlugin;
    }

    /**
     * @param $description
     * @return Discount
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->addModifiedProperty('description');

        return $this;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $displayName
     * @return Discount
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        $this->addModifiedProperty('displayName');

        return $this;
    }

    /**
     * @return null
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param $fkDiscountVoucherPool
     * @return Discount
     */
    public function setFkDiscountVoucherPool($fkDiscountVoucherPool)
    {
        $this->fkDiscountVoucherPool = $fkDiscountVoucherPool;
        $this->addModifiedProperty('fkDiscountVoucherPool');

        return $this;
    }

    /**
     * @return null
     */
    public function getFkDiscountVoucherPool()
    {
        return $this->fkDiscountVoucherPool;
    }

    /**
     * @param $idDiscount
     * @return Discount
     */
    public function setIdDiscount($idDiscount)
    {
        $this->idDiscount = $idDiscount;
        $this->addModifiedProperty('idDiscount');

        return $this;
    }

    /**
     * @return null
     */
    public function getIdDiscount()
    {
        return $this->idDiscount;
    }

    /**
     * @param $isActive
     * @return Discount
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        $this->addModifiedProperty('isActive');

        return $this;
    }

    /**
     * @return null
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param $isPrivileged
     * @return Discount
     */
    public function setIsPrivileged($isPrivileged)
    {
        $this->isPrivileged = $isPrivileged;
        $this->addModifiedProperty('isPrivileged');

        return $this;
    }

    /**
     * @return null
     */
    public function getIsPrivileged()
    {
        return $this->isPrivileged;
    }

    /**
     * @param $validFrom
     * @return Discount
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
        $this->addModifiedProperty('validFrom');

        return $this;
    }

    /**
     * @return null
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param $validTo
     * @return Discount
     */
    public function setValidTo($validTo)
    {
        $this->validTo = $validTo;
        $this->addModifiedProperty('validTo');

        return $this;
    }

    /**
     * @return null
     */
    public function getValidTo()
    {
        return $this->validTo;
    }
}

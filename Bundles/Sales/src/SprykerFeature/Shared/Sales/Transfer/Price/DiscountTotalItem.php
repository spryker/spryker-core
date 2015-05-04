<?php

namespace Generated\Shared\Transfer\SalesPriceTransfer;

use Generated\Shared\Transfer\Calculation\DependencyDiscountTotalItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class DiscountTotalItem extends AbstractTransfer implements DiscountTotalItemInterface
{

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $discountType = null;

    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @var array|string[]
     */
    protected $codes = [];

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
     * @param string $discountType
     *
     * @return $this
     */
    public function setDiscountType($discountType)
    {
        $this->discountType = $discountType;
        $this->addModifiedProperty('discountType');

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discountType;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->addModifiedProperty('amount');

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param array|string[] $codes
     *
     * @return $this
     */
    public function setCodes(array $codes)
    {
        $this->codes = $codes;
        $this->addModifiedProperty('codes');

        return $this;
    }

    /**
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function addCode($code)
    {
        $this->codes[] = $code;

        return $this;
    }
}

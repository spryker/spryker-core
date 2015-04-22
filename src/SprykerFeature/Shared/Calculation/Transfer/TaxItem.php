<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class TaxItem extends AbstractTransfer implements TaxItemInterface
{

    /**
     * @var float
     */
    protected $percentage = 0.0;

    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @param float $percentage
     *
     * @return $this
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        $this->addModifiedProperty('percentage');
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
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
}

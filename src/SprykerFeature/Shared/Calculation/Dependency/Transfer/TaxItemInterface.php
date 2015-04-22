<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

interface TaxItemInterface extends TransferInterface
{
    /**
     * @param float $percentage
     *
     * @return $this
     */
    public function setPercentage($percentage);

    /**
     * @return float
     */
    public function getPercentage();

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount($amount);

    /**
     * @return int
     */
    public function getAmount();
}
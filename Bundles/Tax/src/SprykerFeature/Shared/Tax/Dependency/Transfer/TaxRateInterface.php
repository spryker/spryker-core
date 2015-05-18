<?php

namespace SprykerFeature\Shared\Tax\Dependency\Transfer;

use SprykerEngine\Shared\Transfer\TransferInterface;

interface TaxRateInterface extends TransferInterface
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

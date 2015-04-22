<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

interface DiscountItemInterface
{

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param $amount
     */
    public function setAmount($amount);

    /**
     * @return string
     */
    public function getDisplayName();
}

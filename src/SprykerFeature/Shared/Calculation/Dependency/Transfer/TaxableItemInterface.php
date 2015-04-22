<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface TaxableItemInterface
{
    /**
     * @return int
     */
    public function getTaxPercentage();

    /**
     * @return int
     */
    public function getPriceToPay();
}

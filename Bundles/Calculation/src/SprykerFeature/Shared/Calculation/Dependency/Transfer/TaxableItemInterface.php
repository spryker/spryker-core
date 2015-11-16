<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface TaxableItemInterface
{

    /**
     * @return int
     */
    public function getTaxPercentage();

    /**
     * @param int $taxPercentage
     *
     * @return self
     */
    public function setTaxPercentage($taxPercentage);

    /**
     * @return int
     */
    public function getPriceToPay();

    /**
     * @param int $price
     *
     * @return self
     */
    public function setPriceToPay($price);

}

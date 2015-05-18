<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerFeature\Shared\Tax\Dependency\Transfer\TaxableItemInterface;

interface ExpenseItemInterface extends TaxableItemInterface, PriceItemInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \ArrayObject
     */
    public function getDiscounts();
}

<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

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
}

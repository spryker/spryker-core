<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface CalculableItemInterface extends
    TaxableItemInterface,
    ExpenseContainerInterface,
    OptionContainerInterface,
    PriceItemInterface
{

}

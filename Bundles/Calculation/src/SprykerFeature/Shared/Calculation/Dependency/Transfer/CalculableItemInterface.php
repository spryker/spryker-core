<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerFeature\Shared\Tax\Dependency\Transfer\TaxableItemInterface;

interface CalculableItemInterface extends
    TaxableItemInterface,
    ExpenseContainerInterface,
    OptionContainerInterface,
    PriceItemInterface
{

}

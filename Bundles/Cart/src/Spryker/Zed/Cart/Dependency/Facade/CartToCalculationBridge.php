<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\CalculationFacade;

class CartToCalculationBridge implements CartToCalculationInterface
{

    /**
     * @var CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param CalculationFacade $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->calculationFacade->recalculate($quoteTransfer);
    }

}

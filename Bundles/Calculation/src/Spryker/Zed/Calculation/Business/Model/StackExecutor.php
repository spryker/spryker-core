<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class StackExecutor
{

    /**
     * @var array
     */
    protected $calculatorStack;

    /**
     * @param \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[] $calculatorStack
     */
    public function __construct(array $calculatorStack)
    {
        $this->calculatorStack = $calculatorStack;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->calculatorStack as $calculator) {
            $calculator->recalculate($quoteTransfer);
        }

        return $quoteTransfer;
    }

}

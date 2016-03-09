<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class StackExecutor implements StackExecutorInterface
{

    /**
     * @var array|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
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

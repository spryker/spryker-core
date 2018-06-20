<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Executor;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class QuoteCalculatorExecutor implements QuoteCalculatorExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected $quoteCalculators;

    /**
     * @param \Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface[]|\Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[] $quoteCalculators
     */
    public function __construct(array $quoteCalculators)
    {
        $this->quoteCalculators = $quoteCalculators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $calculableObjectTransfer = $this->mapCalculableObjectTransfer($quoteTransfer);

        foreach ($this->quoteCalculators as $calculator) {
            if ($calculator instanceof CalculatorPluginInterface) {
                $calculableObjectTransfer = $this->recalculateWithLegacyCalculator(
                    $quoteTransfer,
                    $calculableObjectTransfer,
                    $calculator
                );
            } else {
                $calculator->recalculate($calculableObjectTransfer);
            }
        }

        $quoteTransfer = $this->mapQuoteTransfer($quoteTransfer, $calculableObjectTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function mapCalculableObjectTransfer(QuoteTransfer $quoteTransfer)
    {
        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->fromArray($quoteTransfer->toArray(), true);
        $calculableObjectTransfer->setOriginalQuote($quoteTransfer);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapQuoteTransfer(QuoteTransfer $quoteTransfer, CalculableObjectTransfer $calculableObjectTransfer)
    {
        $quoteTransfer->fromArray($calculableObjectTransfer->toArray(), true);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface $calculatorPlugin
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function recalculateWithLegacyCalculator(
        QuoteTransfer $quoteTransfer,
        CalculableObjectTransfer $calculableObjectTransfer,
        CalculatorPluginInterface $calculatorPlugin
    ) {
        $quoteTransfer = $this->mapQuoteTransfer($quoteTransfer, $calculableObjectTransfer);
        $calculatorPlugin->recalculate($quoteTransfer);
        $calculableObjectTransfer = $this->mapCalculableObjectTransfer($quoteTransfer);

        return $calculableObjectTransfer;
    }
}

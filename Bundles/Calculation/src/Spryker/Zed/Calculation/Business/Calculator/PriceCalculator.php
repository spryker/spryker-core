<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;
use Spryker\Zed\Calculation\CalculationConfig;

class PriceCalculator implements CalculatorInterface
{

    /**
     * @var array|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface[]
     */
    protected $netPriceCalculators;

    /**
     * @var array|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface[]
     */
    protected $grossPriceCalculators;

    /**
     * @var \Spryker\Zed\Calculation\CalculationConfig
     */
    protected $calculatorConfig;

    /**
     * @param array $netPriceCalculators
     * @param array $grossPriceCalculators
     * @param \Spryker\Zed\Calculation\CalculationConfig $calculatorConfig
     */
    public function __construct(
        array $netPriceCalculators,
        array $grossPriceCalculators,
        CalculationConfig $calculatorConfig
    )
    {
        $this->netPriceCalculators = $netPriceCalculators;
        $this->grossPriceCalculators = $grossPriceCalculators;
        $this->calculatorConfig = $calculatorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->setTaxMode($this->calculatorConfig->getTaxMode());

        if ($this->calculatorConfig->getTaxMode() === CalculationConfig::TAX_MODE_NET) {
            $this->executeCalculatorStack($this->netPriceCalculators, $calculableObjectTransfer);
        } else {
            $this->executeCalculatorStack($this->grossPriceCalculators, $calculableObjectTransfer);
        }
    }

    /**
     * @param array|\Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface[] $calculators
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function executeCalculatorStack(array $calculators, CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculators as $calculator) {
            $calculator->recalculate($calculableObjectTransfer);
        }
    }

}

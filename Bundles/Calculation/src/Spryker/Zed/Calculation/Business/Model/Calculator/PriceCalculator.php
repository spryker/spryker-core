<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Shared\Calculation\CalculationTaxMode;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

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
     * @param array|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface[] $netPriceCalculators
     * @param array|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface[] $grossPriceCalculators
     */
    public function __construct(
        array $netPriceCalculators,
        array $grossPriceCalculators
    ) {

        $this->netPriceCalculators = $netPriceCalculators;
        $this->grossPriceCalculators = $grossPriceCalculators;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTaxMode();

        if ($calculableObjectTransfer->getTaxMode() === CalculationTaxMode::TAX_MODE_NET) {
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

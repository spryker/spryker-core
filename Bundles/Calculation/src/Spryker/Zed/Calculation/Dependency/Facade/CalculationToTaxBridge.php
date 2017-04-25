<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Dependency\Facade;

class CalculationToTaxBridge implements CalculationToTaxInterface
{

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\Tax\Business\TaxFacadeInterface $taxFacade
     */
    public function __construct($taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
        return $this->taxFacade->getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate);
    }

    /**
     * @param int $netPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromNetPrice($netPrice, $taxRate)
    {
        return $this->taxFacade->getAccruedTaxAmountFromNetPrice($netPrice, $taxRate);
    }

    /**
     * @return void
     */
    public function resetAccruedTaxCalculatorRoundingErrorDelta()
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();
    }

}

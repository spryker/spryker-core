<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Dependency\Facade;

class DiscountSalesAggregatorConnectorToTaxBridge implements DiscountSalesAggregatorConnectorToTaxInterface
{

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\Tax\Business\TaxFacade $taxFacade
     */
    public function __construct($taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param int $grossPrice
     * @param int $taxRate
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
        return $this->taxFacade->getTaxAmountFromGrossPrice($grossPrice, $taxRate);
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
     * @return void
     */
    public function resetAccruedTaxCalculatorRoundingErrorDelta()
    {
        $this->taxFacade->resetAccruedTaxCalculatorRoundingErrorDelta();
    }

}

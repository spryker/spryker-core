<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Spryker\Zed\Tax\Business\TaxFacade;

class DiscountCalculationToTaxBridge implements DiscountCalculationToTaxInterface
{

    /**
     * @var TaxFacade
     */
    protected $taxFacade;

    /**
     * @param TaxFacade $taxFacade
     */
    public function __construct($taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param int $grossPrice
     * @param float $taxRate
     * @param bool $round
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate, $round = true)
    {
        return $this->taxFacade->getTaxAmountFromGrossPrice($grossPrice, $taxRate, $round);
    }

    /**
     * @api
     *
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
       return $this->taxFacade->getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate);
    }
}

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Facade;

use Spryker\Zed\Tax\Business\TaxFacade;

class DiscountToTaxBridge implements DiscountToTaxBridgeInterface
{

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacade
     */
    protected $taxFacade;

    /**
     * DiscountToTax constructor.
     *
     * @param $taxFacade
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

}

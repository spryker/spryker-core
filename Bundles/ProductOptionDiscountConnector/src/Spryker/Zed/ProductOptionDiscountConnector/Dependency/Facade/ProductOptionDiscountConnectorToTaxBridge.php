<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade;

use Spryker\Zed\Tax\Business\TaxFacade;

class ProductOptionDiscountConnectorToTaxBridge implements ProductOptionToTaxBridgeInterface
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
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
        return $this->taxFacade->getTaxAmountFromGrossPrice($grossPrice, $taxRate);
    }
}

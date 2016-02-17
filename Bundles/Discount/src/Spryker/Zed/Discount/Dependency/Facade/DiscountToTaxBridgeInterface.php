<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Discount\Dependency\Facade;

interface DiscountToTaxBridgeInterface
{

    /**
     * @param int $grossPrice
     * @param int $taxRate
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate);

}

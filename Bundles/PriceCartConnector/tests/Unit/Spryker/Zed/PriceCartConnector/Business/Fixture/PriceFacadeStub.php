<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\PriceCartConnector\Business\Fixture;

use Spryker\Zed\Price\Business\PriceFacade;

class PriceFacadeStub extends PriceFacade
{

    private $prices = [];

    private $validities = [];

    public function getPriceBySku($sku, $priceType = null)
    {
        return $this->prices[$sku];
    }

    public function hasValidPrice($sku, $priceType = null)
    {
        if (!isset($this->validities[$sku])) {
            return false;
        }

        return $this->validities[$sku];
    }

    /**
     * @return void
     */
    public function addPriceStub($sku, $price)
    {
        $this->prices[$sku] = $price;
    }

    /**
     * @return void
     */
    public function addValidityStub($sku, $validity = true)
    {
        $this->validities[$sku] = $validity;
    }

}

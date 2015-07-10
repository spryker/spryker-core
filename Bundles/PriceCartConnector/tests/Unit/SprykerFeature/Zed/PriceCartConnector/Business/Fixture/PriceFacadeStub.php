<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Price\Business\PriceFacade;

class PriceFacadeStub extends PriceFacade
{

    private $prices = [];
    private $validities = [];

    public function __construct(FactoryInterface $factory = null, Locator $locator = null)
    {
    }

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

    public function addPriceStub($sku, $price)
    {
        $this->prices[$sku] = $price;
    }

    public function addValidityStub($sku, $validity = true)
    {
        $this->validities[$sku] = $validity;
    }

}

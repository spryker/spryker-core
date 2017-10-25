<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use Spryker\Zed\PriceProduct\Business\PriceProductFacade;

class PriceFacadeStub extends PriceProductFacade
{
    /**
     * @var array
     */
    private $prices = [];

    /**
     * @var array
     */
    private $validities = [];

    /**
     * @param string $sku
     * @param string|null $priceType
     * @param string $currencyCode
     * @param string $priceMode
     *
     * @return mixed
     */
    public function getPriceBySku($sku, $priceType, $currencyCode, $priceMode)
    {
        return $this->prices[$sku];
    }

    /**
     * @param string $sku
     * @param string|null $priceType
     * @param string $currencyCode
     * @param string $priceMode
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType, $currencyCode, $priceMode)
    {
        if (!isset($this->validities[$sku])) {
            return false;
        }

        return $this->validities[$sku];
    }

    /**
     * @param string $sku
     * @param int $price
     *
     * @return void
     */
    public function addPriceStub($sku, $price)
    {
        $this->prices[$sku] = $price;
    }

    /**
     * @param string $sku
     * @param bool $validity
     *
     * @return void
     */
    public function addValidityStub($sku, $validity = true)
    {
        $this->validities[$sku] = $validity;
    }
}

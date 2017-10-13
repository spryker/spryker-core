<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Dependency\Facade;

class PriceCartToPriceBridge implements PriceCartToPriceInterface
{

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Price\Business\PriceFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType, $currencyIsoCode, $priceMode)
    {
        return $this->priceFacade->hasValidPrice($sku, $priceType, $currencyIsoCode, $priceMode);
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType, $currencyIsoCode, $priceMode)
    {
        return $this->priceFacade->getPriceBySku($sku, $priceType, $currencyIsoCode, $priceMode);
    }

}

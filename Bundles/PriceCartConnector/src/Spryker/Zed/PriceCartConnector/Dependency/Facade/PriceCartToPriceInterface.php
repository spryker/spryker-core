<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Dependency\Facade;

interface PriceCartToPriceInterface
{

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null);

    /**
     * @param string $sku
     * @param null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null);

}

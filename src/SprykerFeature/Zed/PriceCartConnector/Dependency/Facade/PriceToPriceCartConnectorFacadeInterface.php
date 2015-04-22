<?php

namespace SprykerFeature\Zed\AvailabilityCartConnector\Dependency\Facade;

interface PriceToPriceCartConnectorFacadeInterface
{
    /**
     * @param $sku
     * @param null $priceType
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null);
}
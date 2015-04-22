<?php

namespace SprykerFeature\Zed\Cart\Dependency\Plugin;

interface GetPricePluginInterface
{

    /**
     * @param string $sku
     * @param null|string $priceType
     * @return int
     */
    public function getPrice($sku, $priceType = null);
}
<?php

namespace SprykerFeature\Zed\Cart\Dependency\Plugin;

interface CheckAvailabilityPluginInterface
{
    /**
     * @param string $sku
     * @param int $quantity
     * @return bool
     */
    public function isProductSellable($sku, $quantity);
}
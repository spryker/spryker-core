<?php

namespace SprykerFeature\Zed\Checkout\Dependency\Plugin;

interface CheckAvailabilityPluginInterface
{
    /**
     * @param string $sku
     * @param int $quantity
     * @return bool
     */
    public function isProductSellable($sku, $quantity);
}
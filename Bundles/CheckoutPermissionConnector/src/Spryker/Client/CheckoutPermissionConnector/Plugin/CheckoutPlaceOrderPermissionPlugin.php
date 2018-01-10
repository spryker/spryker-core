<?php

namespace Spryker\Client\CheckoutPermissionConnector\Plugin;

use Spryker\Client\Permission\Plugin\PermissionPluginInterface;

/**
 * @example
 */
class CheckoutPlaceOrderPermissionPlugin implements PermissionPluginInterface
{
    const KEY = 'permission.checkout.placeOrder';
    /**
     * @return string
     */
    public function getKey()
    {
        return self::KEY;
    }
}
<?php


namespace Spryker\Client\CheckoutPermissionConnector\Plugin;


use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;

/**
 * @example
 */
class CheckoutPlaceOrderGrantTotalXPermissionPlugin implements ExecutablePermissionPluginInterface
{
    const KEY = 'permission.checkout.placeOrder.grantTotal.x';
    const OPTION_CART_MAX_GRAND_TOTAL = 1000;

    /**
     * @param array $configuration
     * @param array|int|null|string $amount
     *
     * @return bool
     */
    public function can(array $configuration, $amount = null)
    {
        return $amount < static::OPTION_CART_MAX_GRAND_TOTAL;
    }

    /**
     * @return array
     */
    public function getConfigurationSignature()
    {
        return [];
    }


    /**
     * @return string
     */
    public function getKey()
    {
        return static::KEY;
    }
}
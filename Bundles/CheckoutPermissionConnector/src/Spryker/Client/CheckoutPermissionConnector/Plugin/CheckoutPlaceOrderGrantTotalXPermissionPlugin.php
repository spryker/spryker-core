<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutPermissionConnector\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

/**
 * @example
 */
class CheckoutPlaceOrderGrantTotalXPermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'permission.checkout.placeOrder.grantTotal.x';
    public const OPTION_CART_MAX_GRAND_TOTAL = 1000;

    /**
     * @param array $configuration
     * @param array|int|string|null $centAmount
     *
     * @return bool
     */
    public function can(array $configuration, $centAmount = null)
    {
        return $centAmount < static::OPTION_CART_MAX_GRAND_TOTAL;
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
    public function getKey(): string
    {
        return static::KEY;
    }
}

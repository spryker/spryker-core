<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutPermissionConnector\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * @example
 */
class CheckoutPlaceOrderPermissionPlugin implements PermissionPluginInterface
{
    public const KEY = 'permission.checkout.placeOrder';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return self::KEY;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessPermission;

use Spryker\Client\CustomerAccessPermission\Exception\PermissionPluginNotFoundException;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeAddToCartPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeOrderPlaceSubmitPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeePricePermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeWishlistPermissionPlugin;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CustomerAccessPermissionConfig extends AbstractBundleConfig
{
    /**
     * Constant used to connect zed content type access settings with the content type permission plugin used in yves shop
     */
    protected const CONTENT_TYPE_PERMISSION_PLUGIN = [
        'price' => SeePricePermissionPlugin::KEY,
        'add-to-cart' => SeeAddToCartPermissionPlugin::KEY,
        'order-place-submit' => SeeOrderPlaceSubmitPermissionPlugin::KEY,
        'wishlist' => SeeWishlistPermissionPlugin::KEY,
    ];

    protected const MESSAGE_PLUGIN_NOT_FOUND_EXCEPTION = 'Plugin not found';

    /**
     * @param string $contentType
     *
     * @throws \Spryker\Client\CustomerAccessPermission\Exception\PermissionPluginNotFoundException
     *
     * @return string
     */
    public function getPluginNameToSeeContentType(string $contentType): string
    {
        if (!array_key_exists($contentType, static::CONTENT_TYPE_PERMISSION_PLUGIN)) {
            throw new PermissionPluginNotFoundException(static::MESSAGE_PLUGIN_NOT_FOUND_EXCEPTION);
        }

        return static::CONTENT_TYPE_PERMISSION_PLUGIN[$contentType];
    }
}

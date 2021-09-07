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
use Spryker\Client\CustomerAccessPermission\Plugin\SeeShoppingListPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeWishlistPermissionPlugin;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CustomerAccessPermissionConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\CustomerAccess\CustomerAccessConfig::CONTENT_TYPE_PRICE
     * @var string
     */
    public const CONTENT_TYPE_PRICE = 'price';

    /**
     * @uses \Spryker\Shared\CustomerAccess\CustomerAccessConfig::CONTENT_TYPE_ORDER_PLACE_SUBMIT
     * @var string
     */
    public const CONTENT_TYPE_ORDER_PLACE_SUBMIT = 'order-place-submit';

    /**
     * @uses \Spryker\Shared\CustomerAccess\CustomerAccessConfig::CONTENT_TYPE_ADD_TO_CART
     * @var string
     */
    public const CONTENT_TYPE_ADD_TO_CART = 'add-to-cart';

    /**
     * @uses \Spryker\Shared\CustomerAccess\CustomerAccessConfig::CONTENT_TYPE_WISHLIST
     * @var string
     */
    public const CONTENT_TYPE_WISHLIST = 'wishlist';

    /**
     * @uses \Spryker\Shared\CustomerAccess\CustomerAccessConfig::CONTENT_TYPE_SHOPPING_LIST
     * @var string
     */
    public const CONTENT_TYPE_SHOPPING_LIST = 'shopping-list';

    /**
     * Constant used to connect zed content type access settings with the content type permission plugin used in yves shop
     *
     * @phpstan-var array<string, string>
     * @var array
     */
    protected const CONTENT_TYPE_PERMISSION_PLUGIN = [
        self::CONTENT_TYPE_PRICE => SeePricePermissionPlugin::KEY,
        self::CONTENT_TYPE_ADD_TO_CART => SeeAddToCartPermissionPlugin::KEY,
        self::CONTENT_TYPE_ORDER_PLACE_SUBMIT => SeeOrderPlaceSubmitPermissionPlugin::KEY,
        self::CONTENT_TYPE_WISHLIST => SeeWishlistPermissionPlugin::KEY,
        self::CONTENT_TYPE_SHOPPING_LIST => SeeShoppingListPermissionPlugin::KEY,
    ];

    /**
     * @phpstan-var array<string, string>
     * @var array
     */
    protected const CONTENT_TYPE_PERMISSION_ACCESS = [
        self::CONTENT_TYPE_ADD_TO_CART => '|^(/en|/de)?/cart(?!/add)',
        self::CONTENT_TYPE_ORDER_PLACE_SUBMIT => '|^(/en|/de)?/checkout',
    ];

    /**
     * @var string
     */
    protected const MESSAGE_PLUGIN_NOT_FOUND_EXCEPTION = 'Plugin not found';

    /**
     * @uses \Spryker\Shared\Customer\CustomerConstants::CUSTOMER_SECURED_PATTERN
     * @var string
     */
    protected const CUSTOMER_SECURED_PATTERN = 'CUSTOMER_SECURED_PATTERN';

    /**
     * @api
     *
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

    /**
     * @api
     *
     * @param string $contentType
     *
     * @return string
     */
    public function getCustomerAccessByContentType(string $contentType): string
    {
        if (!array_key_exists($contentType, static::CONTENT_TYPE_PERMISSION_ACCESS)) {
            return '';
        }

        return static::CONTENT_TYPE_PERMISSION_ACCESS[$contentType];
    }

    /**
     * @api
     *
     * @param string $contentType
     *
     * @return bool
     */
    public function hasPluginToSeeContentType(string $contentType): bool
    {
        return array_key_exists($contentType, static::CONTENT_TYPE_PERMISSION_PLUGIN);
    }

    /**
     * @api
     *
     * @deprecated Functionality was moved to Customer module. Method will be removed without replacement.
     *
     * @return string
     */
    public function getCustomerSecuredPattern(): string
    {
        return $this->get(static::CUSTOMER_SECURED_PATTERN);
    }
}

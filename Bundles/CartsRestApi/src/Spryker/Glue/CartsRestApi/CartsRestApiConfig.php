<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CARTS = 'carts';
    public const RESOURCE_CART_ITEMS = 'items';
    public const RESOURCE_GUEST_CARTS = 'guest-carts';
    public const RESOURCE_GUEST_CARTS_ITEMS = 'guest-cart-items';

    public const CONTROLLER_CARTS = 'carts-resource';
    public const CONTROLLER_CART_ITEMS = 'cart-items-resource';
    public const CONTROLLER_GUEST_CARTS = 'guest-carts-resource';
    public const CONTROLLER_GUEST_CART_ITEMS = 'guest-cart-items-resource';

    public const ACTION_CARTS_GET = 'get';
    public const ACTION_CARTS_POST = 'post';
    public const ACTION_CARTS_DELETE = 'delete';

    public const ACTION_CART_ITEMS_POST = 'post';
    public const ACTION_CART_ITEMS_PATCH = 'patch';
    public const ACTION_CART_ITEMS_DELETE = 'delete';

    public const ACTION_GUEST_CARTS_GET = 'get';

    public const ACTION_GUEST_CART_ITEMS_POST = 'post';
    public const ACTION_GUEST_CART_ITEMS_PATCH = 'patch';
    public const ACTION_GUEST_CART_ITEMS_DELETE = 'delete';

    public const RESPONSE_CODE_CART_NOT_FOUND = '101';
    public const RESPONSE_CODE_ITEM_VALIDATION = '102';
    public const RESPONSE_CODE_ITEM_NOT_FOUND = '103';
    public const RESPONSE_CODE_CART_ID_MISSING = '104';
    public const RESPONSE_CODE_FAILED_DELETING_CART = '105';
    public const RESPONSE_CODE_FAILED_DELETING_CART_ITEM = '106';
    public const RESPONSE_CODE_FAILED_CREATING_CART = '107';
    public const RESPONSE_CODE_MISSING_REQUIRED_PARAMETER = '108';
    public const RESPONSE_CODE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY = '109';
    public const RESPONSE_CODE_CUSTOMER_ALREADY_HAS_CART = '110';

    public const EXCEPTION_MESSAGE_CART_ID_MISSING = 'Cart uuid is missing.';
    public const EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND = 'Item with the given group key not found in the cart.';
    public const EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART = 'Failed to create cart.';
    public const EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND = 'Cart with given uuid not found.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_CART = 'Cart could not be deleted.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM = 'Cart item could not be deleted.';
    public const EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER = 'Cart uuid or item group key is not specified.';
    public const EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY = 'Anonymous customer unique id is empty.';
    public const EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART = 'Customer already has a cart.';

    public const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    protected const GUEST_CART_RESOURCES = [
        CartsRestApiConfig::RESOURCE_GUEST_CARTS,
        CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
    ];

    /**
     * @return string[]
     */
    public function getGuestCartResources(): array
    {
        return static::GUEST_CART_RESOURCES;
    }
}

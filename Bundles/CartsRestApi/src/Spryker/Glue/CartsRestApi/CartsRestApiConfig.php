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

    public const RESPONSE_CODE_QUOTE_NOT_FOUND = '101';
    public const RESPONSE_CODE_ITEM_VALIDATION = '102';
    public const RESPONSE_CODE_ITEM_NOT_FOUND = '103';
    public const RESPONSE_CODE_QUOTE_ID_MISSING = '104';
    public const RESPONSE_CODE_FAILED_DELETING_QUOTE = '105';
    public const RESPONSE_CODE_FAILED_DELETING_QUOTE_ITEM = '106';
    public const RESPONSE_CODE_FAILED_CREATING_QUOTE = '107';
    public const RESPONSE_CODE_MISSING_REQUIRED_PARAMETER = '108';
    public const RESPONSE_CODE_CUSTOMER_UNAUTHORIZED = '109';

    public const EXCEPTION_MESSAGE_QUOTE_ID_MISSING = 'Quote identifier is required.';
    public const EXCEPTION_MESSAGE_QUOTE_ITEM_NOT_FOUND = 'Quote item \'%s\' not found.';
    public const EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART = 'Failed to create quote.';
    public const EXCEPTION_MESSAGE_QUOTE_WITH_ID_NOT_FOUND = 'Quote with id \'%s\' not found.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_QUOTE = 'Quote could not be deleted.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_QUOTE_ITEM = 'Quote item could not be deleted.';
    public const EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER = 'Quote id or item id is not specified.';
    public const EXCEPTION_MESSAGE_QUOTE_NOT_FOUND = 'Cart with id \'%s\' not found.';
    public const EXCEPTION_MESSAGE_CUSTOMER_UNAUTHORIZED = 'Customer unique id is missing.';

    public const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';
}

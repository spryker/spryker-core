<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CARTS = 'carts';
    public const RESOURCE_CART_ITEMS = 'items';

    public const CONTROLLER_CARTS = 'carts-resource';
    public const CONTROLLER_CART_ITEMS = 'cart-items-resource';

    public const ACTION_CARTS_GET = 'get';
    public const ACTION_CARTS_POST = 'post';
    public const ACTION_CARTS_DELETE = 'delete';

    public const ACTION_CART_ITEMS_POST = 'post';
    public const ACTION_CART_ITEMS_PATCH = 'patch';
    public const ACTION_CART_ITEMS_DELETE = 'delete';

    public const RESPONSE_CODE_QUOTE_NOT_FOUND = '101';
    public const RESPONSE_CODE_QUOTE_VALIDATION = '102';
    public const RESPONSE_CODE_ITEM_VALIDATION = '103';
    public const RESPONSE_CODE_VOUCHER_NOT_SET = '104';
    public const RESPONSE_CODE_ITEM_NOT_FOUND = '105';
    public const RESPONSE_CODE_QUOTE_ID_MISSING = '106';
    public const RESPONSE_CODE_FAILED_ADDING_ITEM = '107';

    public const EXCEPTION_MESSAGE_QUOTE_ID_MISSING = 'Quote identifier is required, none provided';
    public const EXCEPTION_MESSAGE_QUOTE_NOT_FOUND = 'Quote not found';
    public const EXCEPTION_MESSAGE_QUOTE_ITEM_NOT_FOUND = 'Quote item not found';
    public const EXCEPTION_MESSAGE_USER_MISSING = 'User is not found';
}

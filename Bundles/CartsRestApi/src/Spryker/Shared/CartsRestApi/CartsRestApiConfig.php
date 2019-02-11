<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CartsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
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
    public const RESPONSE_CODE_PRICE_MODE_DATA_IS_INCORRECT = '111';
    public const RESPONSE_CODE_PRICE_MODE_DATA_IS_MISSING = '112';
    public const RESPONSE_CODE_CURRENCY_DATA_IS_INCORRECT = '113';
    public const RESPONSE_CODE_CURRENCY_DATA_IS_MISSING = '114';
    public const RESPONSE_CODE_CART_CANT_BE_UPDATED = '115';
    public const RESPONSE_CODE_STORE_DATA_IS_MISSING = '116';
    public const RESPONSE_CODE_PERMISSION_FAILED = '117';
    public const RESPONSE_CODE_STORE_DATA_IS_INVALID = '118';

    public const EXCEPTION_MESSAGE_CART_ID_MISSING = 'Cart uuid is missing.';
    public const EXCEPTION_MESSAGE_ITEM_VALIDATION = 'Product sku is missing.';
    public const EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND = 'Item with the given group key not found in the cart.';
    public const EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART = 'Failed to create cart.';
    public const EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND = 'Cart with given uuid not found.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_CART = 'Cart could not be deleted.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM = 'Cart item could not be deleted.';
    public const EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER = 'Cart uuid or item group key is not specified.';
    public const EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY = 'Anonymous customer unique id is empty.';
    public const EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART = 'Customer already has a cart.';
    public const EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'Price mode data is incorrect';
    public const EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'Price mode data is missing';
    public const EXCEPTION_MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'Currency data is incorrect';
    public const EXCEPTION_MESSAGE_CURRENCY_DATA_IS_MISSING = 'Currency data is missing';
    public const EXCEPTION_MESSAGE_PRICE_MODE_CANT_BE_CHANGED = 'Can’t switch price mode when there are items in the cart.';
    public const EXCEPTION_MESSAGE_STORE_DATA_IS_MISSING = 'Store data is missing.';
    public const EXCEPTION_MESSAGE_PERMISSION_FAILED = 'Permission failed.';
    public const EXCEPTION_MESSAGE_STORE_DATA_IS_INVALID = 'Store data is invalid.';

    public const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';
}

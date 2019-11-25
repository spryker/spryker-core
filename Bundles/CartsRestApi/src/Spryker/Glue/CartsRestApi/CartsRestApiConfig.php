<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

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

    public const RESPONSE_CODE_CART_NOT_FOUND = '101';
    public const RESPONSE_CODE_ITEM_VALIDATION = '102';
    public const RESPONSE_CODE_ITEM_NOT_FOUND = '103';
    public const RESPONSE_CODE_CART_ID_MISSING = '104';
    public const RESPONSE_CODE_FAILED_DELETING_CART = '105';
    public const RESPONSE_CODE_FAILED_DELETING_CART_ITEM = '106';
    public const RESPONSE_CODE_FAILED_CREATING_CART = '107';
    public const RESPONSE_CODE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY = '109';
    public const RESPONSE_CODE_CUSTOMER_ALREADY_HAS_CART = '110';
    public const RESPONSE_CODE_CART_CANT_BE_UPDATED = '111';
    public const RESPONSE_CODE_STORE_DATA_IS_INVALID = '112';
    public const RESPONSE_CODE_FAILED_ADDING_CART_ITEM = '113';
    public const RESPONSE_CODE_FAILED_UPDATING_CART_ITEM = '114';
    public const RESPONSE_CODE_UNAUTHORIZED_CART_ACTION = '115';
    public const RESPONSE_CODE_CURRENCY_DATA_IS_MISSING = '116';
    public const RESPONSE_CODE_CURRENCY_DATA_IS_INCORRECT = '117';
    public const RESPONSE_CODE_PRICE_MODE_DATA_IS_MISSING = '118';
    public const RESPONSE_CODE_PRICE_MODE_DATA_IS_INCORRECT = '119';

    public const EXCEPTION_MESSAGE_CART_ID_MISSING = 'Cart uuid is missing.';
    public const EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND = 'Item with the given group key not found in the cart.';
    public const EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART = 'Failed to create cart.';
    public const EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND = 'Cart with given uuid not found.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_CART = 'Cart could not be deleted.';
    public const EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM = 'Cart item could not be deleted.';
    public const EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY = 'Anonymous customer unique id is empty.';
    public const EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART = 'Customer already has a cart.';
    public const EXCEPTION_MESSAGE_PRICE_MODE_CANT_BE_CHANGED = 'Can’t switch price mode when there are items in the cart.';
    public const EXCEPTION_MESSAGE_STORE_DATA_IS_INVALID = 'Store data is invalid.';
    public const EXCEPTION_MESSAGE_FAILED_ADDING_CART_ITEM = 'Cart item could not be added.';
    public const EXCEPTION_MESSAGE_FAILED_UPDATING_CART_ITEM = 'Cart item could not be updated.';
    public const EXCEPTION_MESSAGE_UNAUTHORIZED_CART_ACTION = 'Unauthorized cart action.';
    public const EXCEPTION_MESSAGE_CURRENCY_DATA_IS_MISSING = 'Currency is missing.';
    public const EXCEPTION_MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'Currency is incorrect.';
    public const EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'Price mode is missing.';
    public const EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'Price mode is incorrect.';

    public const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    protected const GUEST_CART_RESOURCES = [
        CartsRestApiConfig::RESOURCE_GUEST_CARTS,
        CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
    ];

    /**
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATING_CART => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_CREATING_CART,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_CANT_BE_UPDATED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CART_CANT_BE_UPDATED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_PRICE_MODE_CANT_BE_CHANGED,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_ITEM_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_ITEM_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_DELETING_CART => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_DELETING_CART,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_DELETING_CART,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_ADDING_CART_ITEM => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_ADDING_CART_ITEM,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_ADDING_CART_ITEM,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_UPDATING_CART_ITEM => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_UPDATING_CART_ITEM,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_UPDATING_CART_ITEM,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_DELETING_CART_ITEM => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_FAILED_DELETING_CART_ITEM,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_CUSTOMER_ALREADY_HAS_CART => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CUSTOMER_ALREADY_HAS_CART,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_STORE_DATA_IS_INVALID => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_STORE_DATA_IS_INVALID,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_STORE_DATA_IS_INVALID,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_UNAUTHORIZED_CART_ACTION,
                RestErrorMessageTransfer::STATUS => Response::HTTP_FORBIDDEN,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_UNAUTHORIZED_CART_ACTION,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_CURRENCY_DATA_IS_MISSING => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CURRENCY_DATA_IS_MISSING,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CURRENCY_DATA_IS_MISSING,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_CURRENCY_DATA_IS_INCORRECT => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_CURRENCY_DATA_IS_INCORRECT,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_CURRENCY_DATA_IS_INCORRECT,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_INCORRECT => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_PRICE_MODE_DATA_IS_INCORRECT,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_INCORRECT,
            ],
            CartsRestApiSharedConfig::ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_MISSING => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_PRICE_MODE_DATA_IS_MISSING,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_MISSING,
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function getGuestCartResources(): array
    {
        return static::GUEST_CART_RESOURCES;
    }
}

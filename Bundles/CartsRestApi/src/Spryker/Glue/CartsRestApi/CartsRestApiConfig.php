<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as SharedCartsRestApiConfig;
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

    public const ACTION_CARTS_GET = 'get';
    public const ACTION_CARTS_POST = 'post';
    public const ACTION_CARTS_DELETE = 'delete';
    public const ACTION_CARTS_PATCH = 'patch';

    public const ACTION_CART_ITEMS_POST = 'post';
    public const ACTION_CART_ITEMS_PATCH = 'patch';
    public const ACTION_CART_ITEMS_DELETE = 'delete';

    public const ACTION_GUEST_CARTS_GET = 'get';
    public const ACTION_GUEST_CARTS_PATCH = 'patch';

    public const ACTION_GUEST_CART_ITEMS_POST = 'post';
    public const ACTION_GUEST_CART_ITEMS_PATCH = 'patch';
    public const ACTION_GUEST_CART_ITEMS_DELETE = 'delete';

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

    public const RESPONSE_UNEXPECTED_HTTP_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const RESPONSE_ERROR_MAP = [
        SharedCartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND => [
            'status' => Response::HTTP_NOT_FOUND,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND => [
            'status' => Response::HTTP_NOT_FOUND,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING => [
            'status' => Response::HTTP_BAD_REQUEST,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_DELETING_CART,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART_ITEM => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART => [
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_MISSING_REQUIRED_PARAMETER => [
            'status' => Response::HTTP_BAD_REQUEST,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY => [
            'status' => Response::HTTP_BAD_REQUEST,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_CUSTOMER_ALREADY_HAS_CART => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_PRICE_MODE_DATA_IS_INCORRECT => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_INCORRECT,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_PRICE_MODE_DATA_IS_MISSING => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_PRICE_MODE_DATA_IS_MISSING,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_CURRENCY_DATA_IS_INCORRECT => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CURRENCY_DATA_IS_INCORRECT,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_CURRENCY_DATA_IS_MISSING => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CURRENCY_DATA_IS_MISSING,
        ],
        SharedCartsRestApiConfig::RESPONSE_CODE_CART_CANT_BE_UPDATED => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedCartsRestApiConfig::EXCEPTION_MESSAGE_PRICE_MODE_CANT_BE_CHANGED,
        ],
    ];
}

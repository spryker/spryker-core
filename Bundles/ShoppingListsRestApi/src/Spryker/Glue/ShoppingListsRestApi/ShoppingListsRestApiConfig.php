<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    public const CONTROLLER_SHOPPING_LIST_ITEMS = 'shopping-list-items-resource';
    public const CONTROLLER_SHOPPING_LISTS = 'shopping-lists-resource';

    public const RESOURCE_SHOPPING_LISTS = 'shopping-lists';
    public const RESOURCE_SHOPPING_LIST_ITEMS = 'shopping-list-items';

    public const ACTION_SHOPPING_LISTS_GET = 'get';
    public const ACTION_SHOPPING_LISTS_POST = 'post';
    public const ACTION_SHOPPING_LISTS_PATCH = 'patch';
    public const ACTION_SHOPPING_LISTS_DELETE = 'delete';

    public const ACTION_SHOPPING_LIST_ITEMS_POST = 'post';
    public const ACTION_SHOPPING_LIST_ITEMS_DELETE = 'delete';
    public const ACTION_SHOPPING_LIST_ITEMS_PATCH = 'patch';

    public const RESPONSE_UNEXPECTED_HTTP_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;
    public const RESPONSE_ERROR_MAP = [
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED => [
            'status' => Response::HTTP_BAD_REQUEST,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED => [
            'status' => Response::HTTP_BAD_REQUEST,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND => [
            'status' => Response::HTTP_NOT_FOUND,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ITEM_NOT_FOUND => [
            'status' => Response::HTTP_NOT_FOUND,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_ITEM_NOT_FOUND,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED => [
            'status' => Response::HTTP_FORBIDDEN,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_DUPLICATE_NAME,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_WRONG_QUANTITY => [
            'status' => Response::HTTP_BAD_REQUEST,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_DETAIL_SHOPPING_LIST_WRONG_QUANTITY,
        ],
        SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_PRODUCT_NOT_FOUND => [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'detail' => SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_PRODUCT_NOT_FOUND,
        ],
    ];
}

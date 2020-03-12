<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SHOPPING_LISTS = 'shopping-lists';
    public const RESOURCE_SHOPPING_LIST_ITEMS = 'shopping-list-items';

    public const RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED = '1501';
    public const RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED = 'Shopping list id is not specified.';

    public const RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = '1502';
    public const RESPONSE_DETAIL_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = 'Shopping list item id is not specified.';

    public const RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND = '1503';
    public const RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND = 'Shopping list not found.';

    public const RESPONSE_CODE_SHOPPING_LIST_ITEM_NOT_FOUND = '1504';
    public const RESPONSE_DETAIL_SHOPPING_LIST_ITEM_NOT_FOUND = 'Shopping list item not found.';

    public const RESPONSE_CODE_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = '1505';
    public const RESPONSE_DETAIL_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'Requested operation requires write access permission.';

    public const RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME = '1506';
    public const RESPONSE_DETAIL_SHOPPING_LIST_DUPLICATE_NAME = 'Shopping list with given name already exists.';

    public const RESPONSE_CODE_SHOPPING_LIST_WRONG_QUANTITY = '1507';
    public const RESPONSE_DETAIL_SHOPPING_LIST_WRONG_QUANTITY = 'Cannot process quantity of the shopping list item.';

    public const RESPONSE_CODE_SHOPPING_LIST_PRODUCT_NOT_FOUND = '1508';
    public const RESPONSE_DETAIL_SHOPPING_LIST_PRODUCT_NOT_FOUND = 'Concrete product not found.';

    public const RESPONSE_CODE_VALIDATION = '1509';

    public const RESPONSE_CODE_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = '1510';
    public const RESPONSE_DETAIL_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = 'Product is discontinued';

    public const RESPONSE_CODE_LIST_ITEM_PRODUCT_NOT_ACTIVE = '1511';
    public const RESPONSE_DETAIL_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'Product is not active.';

    /**
     * @api
     *
     * @return array
     */
    public static function getErrorIdentifierToRestErrorMapping(): array
    {
        return [
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ID_NOT_SPECIFIED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_ITEM_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_ITEM_NOT_FOUND,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_FORBIDDEN,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_DUPLICATE_NAME => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_DUPLICATE_NAME,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRONG_QUANTITY => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_WRONG_QUANTITY,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_WRONG_QUANTITY,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRODUCT_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_DETAIL_SHOPPING_LIST_PRODUCT_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_CODE_SHOPPING_LIST_PRODUCT_NOT_FOUND,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_LIST_ITEM_PRODUCT_NOT_ACTIVE,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_LIST_ITEM_PRODUCT_NOT_ACTIVE,
            ],
            SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                RestErrorMessageTransfer::DETAIL => static::RESPONSE_DETAIL_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED,
            ],
        ];
    }
}

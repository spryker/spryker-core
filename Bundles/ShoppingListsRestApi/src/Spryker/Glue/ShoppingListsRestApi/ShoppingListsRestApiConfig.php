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
    /**
     * @var string
     */
    public const RESOURCE_SHOPPING_LISTS = 'shopping-lists';

    /**
     * @var string
     */
    public const RESOURCE_SHOPPING_LIST_ITEMS = 'shopping-list-items';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED = '1501';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED = 'Shopping list id is not specified.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = '1502';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = 'Shopping list item id is not specified.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND = '1503';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND = 'Shopping list not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_ITEM_NOT_FOUND = '1504';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_ITEM_NOT_FOUND = 'Shopping list item not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = '1505';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'Requested operation requires write access permission.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME = '1506';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_DUPLICATE_NAME = 'Shopping list with given name already exists.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_WRONG_QUANTITY = '1507';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_WRONG_QUANTITY = 'Cannot process quantity of the shopping list item.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_PRODUCT_NOT_FOUND = '1508';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_PRODUCT_NOT_FOUND = 'Concrete product not found.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_VALIDATION = '1509';

    /**
     * @var string
     */
    public const RESPONSE_CODE_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = '1510';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = 'Product is discontinued';

    /**
     * @var string
     */
    public const RESPONSE_CODE_LIST_ITEM_PRODUCT_NOT_ACTIVE = '1511';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'Product is not active.';

    /**
     * @api
     *
     * @return array<string, array<string, mixed>>
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

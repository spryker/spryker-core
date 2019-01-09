<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShoppingListsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY
     */
    public const X_COMPANY_USER_ID_HEADER_KEY = 'X-Company-User-Id';

    public const RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED = '1501';
    public const RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED = 'Shopping list id is not specified.';
    public const RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = '1502';
    public const RESPONSE_DETAIL_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = 'Shopping list item id is not specified.';
    public const RESPONSE_CODE_COMPANY_USER_NOT_FOUND = '1503';
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND = 'Company user not found.';
    public const RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND = '1504';
    public const RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND = 'Shopping list not found.';
    public const RESPONSE_CODE_SHOPPING_LIST_ITEM_NOT_FOUND = '1505';
    public const RESPONSE_DETAIL_SHOPPING_LIST_ITEM_NOT_FOUND = 'Shopping list item not found.';
    public const RESPONSE_CODE_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = '1506';
    public const RESPONSE_DETAIL_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'Requested operation requires write access permission.';
    public const RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME = '1507';
    public const RESPONSE_DETAIL_SHOPPING_LIST_DUPLICATE_NAME = 'Shopping list with given name already exists.';
    public const RESPONSE_CODE_SHOPPING_LIST_CANNOT_ADD_ITEM = '1508';
    public const RESPONSE_DETAIL_SHOPPING_LIST_CANNOT_ADD_ITEM = 'Cannot add an item to shopping list.';
    public const RESPONSE_CODE_SHOPPING_LIST_CANNOT_UPDATE_ITEM = '1509';
    public const RESPONSE_DETAIL_SHOPPING_LIST_CANNOT_UPDATE_ITEM = 'Cannot update the shopping list item.';
    public const RESPONSE_CODE_SHOPPING_LIST_CANNOT_DELETE_ITEM = '1510';
    public const RESPONSE_DETAIL_SHOPPING_LIST_CANNOT_DELETE_ITEM = 'Cannot delete the shopping list item.';

    /**
     * Do not forget to add mapping:
     * @see \Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig::RESPONSE_ERROR_MAP
     */
}

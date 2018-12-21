<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SHOPPING_LISTS = 'shopping-lists';
    public const RESOURCE_SHOPPING_LIST_ITEMS = 'shopping-list-items';

    public const CONTROLLER_SHOPPING_LIST_ITEMS = 'shopping-list-items-resource';

    public const ACTION_SHOPPING_LIST_ITEMS_POST = 'post';

    public const RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND = '1501';
    public const RESPONSE_CODE_SHOPPING_LIST_CANT_ADD_ITEM = '1506';

    public const RESPONSE_DETAIL_SHOPPING_LIST_CANT_ADD_ITEM = 'Can`t add an item to shopping list';
    public const RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND = 'Shopping list not found.';

    public const COMPANY_USER_HEADER_KEY = 'X-Company-User-Id';
}

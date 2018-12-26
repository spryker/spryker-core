<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShoppingListsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_SHOPPING_LIST_CANNOT_ADD_ITEM = '1506';
    public const RESPONSE_CODE_COMPANY_USER_NOT_FOUND = '1507';
    public const RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND = '1508';

    public const RESPONSE_DETAIL_SHOPPING_LIST_CANNOT_ADD_ITEM = 'Can\'t add an item to shopping list';
    public const RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND = 'Company user not found.';
    public const RESPONSE_DETAIL_SHOPPING_LIST_NOT_FOUND = 'Shopping list not found.';
}

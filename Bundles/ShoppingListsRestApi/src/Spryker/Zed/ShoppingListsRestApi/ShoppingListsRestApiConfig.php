<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi;

use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter::DUPLICATE_NAME_SHOPPING_LIST
     */
    public const DUPLICATE_NAME_SHOPPING_LIST = 'customer.account.shopping_list.error.duplicate_name';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter::CANNOT_UPDATE_SHOPPING_LIST
     */
    public const CANNOT_UPDATE_SHOPPING_LIST = 'customer.account.shopping_list.error.cannot_update';

    public const RESPONSE_ERROR_MAP = [
        self::DUPLICATE_NAME_SHOPPING_LIST => SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME,
        self::CANNOT_UPDATE_SHOPPING_LIST => SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_CANNOT_UPDATE,
    ];
}

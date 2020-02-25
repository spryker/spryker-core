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

    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED
     */
    public const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED = 'customer.account.shopping_list.delete.failed';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidator::ERROR_SHOPPING_LIST_NOT_FOUND
     */
    public const ERROR_SHOPPING_LIST_NOT_FOUND = 'customer.account.shopping_list.error.not_found';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidator::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED
     */
    public const ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'customer.account.shopping_list.error.write_permission_required';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidator::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID
     */
    public const ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID = 'customer.account.shopping_list_item.error.quantity_not_valid';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidator::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND
     */
    public const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND = 'customer.account.shopping_list_item.error.product_not_found';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperation::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE
     */
    public const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'customer.account.shopping_list_item.error.product_not_active';

    /**
     * @see \Spryker\Zed\ProductDiscontinued\Business\ShoppingListCheck\ShoppingListAddItemPreCheck::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED
     */
    public const SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = 'shopping_list.pre.check.product_discontinued';

    /**
     * @return array
     */
    public static function getResponseErrorMapping(): array
    {
        return [
            static::DUPLICATE_NAME_SHOPPING_LIST => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_DUPLICATE_NAME,
            static::CANNOT_UPDATE_SHOPPING_LIST => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            static::ERROR_SHOPPING_LIST_NOT_FOUND => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND,
            static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            static::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRONG_QUANTITY,
            static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRODUCT_NOT_FOUND,
            static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE,
            static::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED,
        ];
    }
}

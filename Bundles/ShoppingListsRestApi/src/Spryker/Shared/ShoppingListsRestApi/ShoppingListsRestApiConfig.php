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
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_ID_NOT_SPECIFIED = 'ERROR_IDENTIFIER_SHOPPING_LIST_ID_NOT_SPECIFIED';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED = 'ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND = 'ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_NOT_FOUND = 'ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_NOT_FOUND';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_DUPLICATE_NAME = 'ERROR_IDENTIFIER_SHOPPING_LIST_DUPLICATE_NAME';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_WRONG_QUANTITY = 'ERROR_IDENTIFIER_SHOPPING_LIST_WRONG_QUANTITY';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_PRODUCT_NOT_FOUND = 'ERROR_IDENTIFIER_SHOPPING_LIST_PRODUCT_NOT_FOUND';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE';
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = 'ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED';
}

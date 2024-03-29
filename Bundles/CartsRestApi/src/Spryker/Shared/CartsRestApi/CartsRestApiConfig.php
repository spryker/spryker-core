<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CartsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CartsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_CART_NOT_FOUND = 'ERROR_IDENTIFIER_CART_NOT_FOUND';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_CART_CANT_BE_UPDATED = 'ERROR_IDENTIFIER_CART_CANT_BE_UPDATED';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_ITEM_NOT_FOUND = 'ERROR_IDENTIFIER_ITEM_NOT_FOUND';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_FAILED_DELETING_CART = 'ERROR_IDENTIFIER_FAILED_DELETING_CART';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_FAILED_DELETING_CART_ITEM = 'ERROR_IDENTIFIER_FAILED_DELETING_CART_ITEM';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_FAILED_ADDING_CART_ITEM = 'ERROR_IDENTIFIER_FAILED_ADDING_CART_ITEM';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_FAILED_UPDATING_CART_ITEM = 'ERROR_IDENTIFIER_FAILED_UPDATING_CART_ITEM';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_FAILED_CREATING_CART = 'ERROR_IDENTIFIER_FAILED_CREATING_CART';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY = 'ERROR_IDENTIFIER_ANONYMOUS_CUSTOMER_UNIQUE_ID_EMPTY';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_CUSTOMER_ALREADY_HAS_CART = 'ERROR_IDENTIFIER_CUSTOMER_ALREADY_HAS_CART';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_STORE_DATA_IS_INVALID = 'ERROR_IDENTIFIER_STORE_DATA_IS_INVALID';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION = 'ERROR_IDENTIFIER_UNAUTHORIZED_CART_ACTION';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_QUOTE_NOT_AVAILABLE = 'ERROR_IDENTIFIER_QUOTE_NOT_AVAILABLE';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_PERMISSION_FAILED = 'ERROR_IDENTIFIER_PERMISSION_FAILED';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_CURRENCY_DATA_IS_MISSING = 'ERROR_IDENTIFIER_CURRENCY_DATA_IS_MISSING';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_CURRENCY_DATA_IS_INCORRECT = 'ERROR_IDENTIFIER_CURRENCY_DATA_IS_INCORRECT';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_MISSING = 'ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_MISSING';

    /**
     * @var string
     */
    public const ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_INCORRECT = 'ERROR_IDENTIFIER_PRICE_MODE_DATA_IS_INCORRECT';
}

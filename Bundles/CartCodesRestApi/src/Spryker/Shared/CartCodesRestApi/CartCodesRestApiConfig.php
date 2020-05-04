<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CartCodesRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CartCodesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
     */
    public const ERROR_IDENTIFIER_CART_NOT_FOUND = 'ERROR_IDENTIFIER_CART_NOT_FOUND';
    public const ERROR_IDENTIFIER_CART_CODE_NOT_FOUND = 'ERROR_IDENTIFIER_CART_CODE_NOT_FOUND';
    public const ERROR_IDENTIFIER_CART_CODE_CANNOT_BE_REMOVED = 'ERROR_IDENTIFIER_CART_CODE_CANNOT_BE_REMOVED';
    public const ERROR_IDENTIFIER_CART_CODE_CANT_BE_ADDED = 'ERROR_IDENTIFIER_CART_CODE_CANT_BE_ADDED';
}

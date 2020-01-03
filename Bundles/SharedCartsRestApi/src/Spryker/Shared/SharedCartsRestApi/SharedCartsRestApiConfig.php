<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SharedCartsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SharedCartsRestApiConfig extends AbstractBundleConfig
{
    public const ERROR_IDENTIFIER_QUOTE_NOT_FOUND = 'ERROR_IDENTIFIER_QUOTE_NOT_FOUND';
    public const ERROR_IDENTIFIER_QUOTE_PERMISSION_GROUP_NOT_FOUND = 'ERROR_IDENTIFIER_QUOTE_PERMISSION_GROUP_NOT_FOUND';
    public const ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND = 'ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND';
    public const ERROR_IDENTIFIER_FAILED_TO_SHARE_CART = 'ERROR_IDENTIFIER_FAILED_TO_SHARE_CART';
    public const ERROR_IDENTIFIER_FAILED_TO_SAVE_SHARED_CART = 'ERROR_IDENTIFIER_FAILED_TO_SAVE_SHARED_CART';
    public const ERROR_IDENTIFIER_ACTION_FORBIDDEN = 'ERROR_IDENTIFIER_ACTION_FORBIDDEN';
}

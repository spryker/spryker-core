<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WishlistsRestApi;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class WishlistsRestApiConfig extends AbstractBundleConfig
{
    public const ERROR_IDENTIFIER_WISHLIST_NOT_FOUND = 'ERROR_IDENTIFIER_WISHLIST_NOT_FOUND';
    public const ERROR_IDENTIFIER_WISHLIST_NAME_ALREADY_EXIST = 'ERROR_IDENTIFIER_WISHLIST_NAME_ALREADY_EXIST';
    public const ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT = 'ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT';
    public const ERROR_IDENTIFIER_WISHLIST_CANT_BE_UPDATED = 'ERROR_IDENTIFIER_WISHLIST_CANT_BE_UPDATED';

    public const ERROR_IDENTIFIER_WISHLIST_ITEM_CANT_BE_ADDED = 'ERROR_IDENTIFIER_WISHLIST_ITEM_CANT_BE_ADDED';
    public const ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST = 'ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST';
}

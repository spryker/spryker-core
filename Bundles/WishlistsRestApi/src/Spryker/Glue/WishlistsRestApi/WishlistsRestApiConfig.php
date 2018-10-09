<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class WishlistsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_WISHLISTS = 'wishlists';
    public const RESOURCE_WISHLIST_ITEMS = 'wishlist-items';

    public const RESPONSE_CODE_WISHLIST_NOT_FOUND = '201';
    public const RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = '202';
    public const RESPONSE_CODE_WISHLIST_CANT_CREATE_WISHLIST = '203';
    public const RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST = '204';
    public const RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM = '206';
    public const RESPONSE_CODE_NO_ITEM_WITH_PROVIDED_ID = '208';

    public const RESPONSE_DETAIL_WISHLIST_NOT_FOUND = 'Can\'t find wishlist.';
    public const RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = 'A wishlist with the same name already exists.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM = 'Can\'t add an item.';
    public const RESPONSE_DETAIL_NO_ITEM_WITH_PROVIDED_ID = 'No item with provided id in wishlist.';
}

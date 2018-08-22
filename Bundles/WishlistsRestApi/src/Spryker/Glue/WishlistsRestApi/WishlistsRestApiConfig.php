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

    public const RESOURCE_RELATION_PRODUCTS = 'products';

    public const RESPONSE_CODE_WISHLIST_NOT_FOUND = '201';
    public const RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = '202';
    public const RESPONSE_CODE_WISHLIST_CANT_CREATE_WISHLIST = '203';
    public const RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST = '204';
    public const RESPONSE_CODE_WISHLIST_CANT_REMOVE_WISHLIST = '205';
    public const RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM = '206';
    public const RESPONSE_CODE_WISHLIST_CANT_REMOVE_ITEM = '207';
    public const RESPONSE_CODE_NO_ITEM_WITH_PROVIDED_SKU = '208';

    public const RESPONSE_DETAIL_WISHLIST_NOT_FOUND = 'Can`t find wishlist.';
    public const RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS = 'A wishlist with the same name already exists.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_CREATE_WISHLIST = 'Can`t create wishlist.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_UPDATE_WISHLIST = 'Can`t update wishlist.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_REMOVE_WISHLIST = 'Can`t remove wishlist.';
    public const RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM = 'Can`t add an item.';

    public const RESPONSE_DETAIL_NO_ITEM_WITH_PROVIDED_SKU = 'No item with provided SKU in wishlist.';
}

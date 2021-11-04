<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;

/**
 * Use this plugin to map additional data from `RestWishlistItemsAttributesTransfer` to `WishlistItemRequestTransfer`.
 */
interface WishlistItemRequestMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `RestWishlistItemsAttributesTransfer` to `WishlistItemRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer
     */
    public function map(
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer,
        WishlistItemRequestTransfer $wishlistItemRequestTransfer
    ): WishlistItemRequestTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface ProductConfigurationRestWishlistItemsAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer
     */
    public function mapRestWishlistItemsAttributesTransferToWishlistItemRequestTransfer(
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer,
        WishlistItemRequestTransfer $wishlistItemRequestTransfer
    ): WishlistItemRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer
     */
    public function mapWishlistItemTransferToRestWishlistItemsAttributesTransfer(
        WishlistItemTransfer $wishlistItemTransfer,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestWishlistItemsAttributesTransfer;
}

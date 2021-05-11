<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOfferWishlistRestApi\Processor\Mapper\Wishlist;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

class MerchantProductOfferWishlistRestApiMapper implements MerchantProductOfferWishlistRestApiMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer
     */
    public function mapWishlistItemTransferToRestWishlistItemsAttributesTransfer(
        WishlistItemTransfer $wishlistItemTransfer,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestWishlistItemsAttributesTransfer {
        if (!$wishlistItemTransfer->getProductOfferReference()) {
            return $restWishlistItemsAttributesTransfer;
        }

        $restWishlistItemsAttributesId = sprintf(
            '%s_%s',
            $wishlistItemTransfer->getSku(),
            $wishlistItemTransfer->getProductOfferReference()
        );

        return $restWishlistItemsAttributesTransfer->setId($restWishlistItemsAttributesId);
    }
}

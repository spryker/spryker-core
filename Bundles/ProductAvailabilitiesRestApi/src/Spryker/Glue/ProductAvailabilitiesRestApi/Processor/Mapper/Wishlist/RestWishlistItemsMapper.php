<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\Wishlist;

use Generated\Shared\Transfer\RestProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

class RestWishlistItemsMapper implements RestWishlistItemsMapperInterface
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
        if (!$wishlistItemTransfer->getProductConcreteAvailability()) {
            return $restWishlistItemsAttributesTransfer;
        }

        $productConcreteAvailabilityTransfer = $wishlistItemTransfer->getProductConcreteAvailability();

        return $restWishlistItemsAttributesTransfer->setAvailability(
            (new RestProductConcreteAvailabilityTransfer())
                ->setisNeverOutOfStock($productConcreteAvailabilityTransfer->getIsNeverOutOfStock())
                ->setQuantity($productConcreteAvailabilityTransfer->getAvailability())
                ->setAvailability($wishlistItemTransfer->getIsSellable())
        );
    }
}

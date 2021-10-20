<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOfferWishlistRestApi\Plugin\Wishlist;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiFactory getFactory()
 */
class ProductOfferRestWishlistItemsAttributesMapperPlugin extends AbstractPlugin implements RestWishlistItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Populates `RestWishlistItemsAttributes.id` with the following pattern: `{WishlistItem.sku}_{WishlistItemTransfer.productOfferReference}`.
     * - Returns `RestWishlistItemsAttributes` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer
     */
    public function map(
        WishlistItemTransfer $wishlistItemTransfer,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestWishlistItemsAttributesTransfer {
        return $this->getFactory()
            ->createMerchantProductOfferWishlistRestApiMapper()
            ->mapWishlistItemTransferToRestWishlistItemsAttributesTransfer(
                $wishlistItemTransfer,
                $restWishlistItemsAttributesTransfer,
            );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi\Plugin\WishlistsRestApi;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiFactory getFactory()
 */
class ProductConfigurationRestWishlistItemsAttributesMapperPlugin extends AbstractPlugin implements RestWishlistItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `WishlistItemTransfer::productConfigurationInstance` is set.
     * - Concatenates product sku with product configuration instance hash.
     * - Sets created reference to `RestWishlistItemsAttributesTransfer::id`.
     * - Maps the `WishlistItemTransfer::productConfigurationInstance` to `RestWishlistItemsAttributes::productConfigurationInstance` transfer object.
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
            ->createProductConfigurationRestWishlistItemsAttributesMapper()
            ->mapWishlistItemTransferToRestWishlistItemsAttributesTransfer(
                $wishlistItemTransfer,
                $restWishlistItemsAttributesTransfer,
            );
    }
}

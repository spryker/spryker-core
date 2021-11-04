<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi\Plugin\WishlistsRestApi;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\WishlistItemRequestMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiFactory getFactory()
 */
class ProductConfigurationWishlistItemRequestMapperPlugin extends AbstractPlugin implements WishlistItemRequestMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `RestWishlistItemsAttributesTransfer::productConfigurationInstance` is set.
     * - Maps the `RestWishlistItemsAttributesTransfer::productConfigurationInstance` to `WishlistItemRequestTransfer::productConfigurationInstance`.
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
    ): WishlistItemRequestTransfer {
        return $this->getFactory()
            ->createProductConfigurationRestWishlistItemsAttributesMapper()
            ->mapRestWishlistItemsAttributesTransferToWishlistItemRequestTransfer(
                $restWishlistItemsAttributesTransfer,
                $wishlistItemRequestTransfer,
            );
    }
}

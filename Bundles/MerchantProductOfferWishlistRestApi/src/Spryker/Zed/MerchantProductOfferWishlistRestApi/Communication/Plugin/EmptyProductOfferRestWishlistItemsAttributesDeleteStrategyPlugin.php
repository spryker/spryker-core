<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface;

/**
 * @deprecated Will be removed in the next major without replacement.
 *
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\MerchantProductOfferWishlistRestApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\Business\MerchantProductOfferWishlistRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferWishlistRestApi\MerchantProductOfferWishlistRestApiConfig getConfig()
 */
class EmptyProductOfferRestWishlistItemsAttributesDeleteStrategyPlugin extends AbstractPlugin implements RestWishlistItemsAttributesDeleteStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if requested wishlist item is exist in wishlist item collection.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return bool
     */
    public function isApplicable(WishlistItemRequestTransfer $wishlistItemRequestTransfer, ArrayObject $wishlistItemTransfers): bool
    {
        return !$wishlistItemRequestTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function delete(WishlistItemRequestTransfer $wishlistItemRequestTransfer, ArrayObject $wishlistItemTransfers): void
    {
        $this->getFacade()
            ->deleteWishlistItemWithoutProductOffer(
                $wishlistItemRequestTransfer,
                $wishlistItemTransfers,
            );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistFactory getFactory()
 */
class ProductConfigurationWishlistClient extends AbstractClient implements ProductConfigurationWishlistClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer
    {
        return $this->getFactory()
            ->createWishlistItemExpander()
            ->expandWithProductConfiguration($wishlistItemTransfer, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function expandWishlistMoveToCartRequestCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
    ): WishlistMoveToCartRequestCollectionTransfer {
        return $this->getFactory()
            ->createProductConfigurationWishlistMoveToCartExpander()
            ->expandWishlistMoveToCartRequestCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                $wishlistMoveToCartRequestCollectionDiffTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function expandWishlistItemCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer,
        WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
    ): WishlistItemCollectionTransfer {
        return $this->getFactory()
            ->createProductConfigurationWishlistExpander()
            ->expandWishlistItemCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                $wishlistItemCollectionTransfer,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->getFactory()
            ->createProductConfiguratorRedirectResolver()
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->getFactory()
            ->createProductConfiguratorResponseProcessor()
            ->processProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseTransfer,
                $configuratorResponseData,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductsWithProductConfigurationPrices(
        array $priceProductTransfers,
        ProductViewTransfer $productViewTransfer
    ): array {
        return $this->getFactory()
            ->createProductConfigurationPriceExpander()
            ->expandPriceProductsWithProductConfigurationPrices(
                $priceProductTransfers,
                $productViewTransfer,
            );
    }
}

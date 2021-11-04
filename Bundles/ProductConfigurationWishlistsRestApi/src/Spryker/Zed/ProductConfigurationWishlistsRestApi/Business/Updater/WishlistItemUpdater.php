<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Reader\WishlistItemReaderInterface;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Facade\ProductConfigurationWishlistsRestApiToWishlistFacadeInterface;

class WishlistItemUpdater implements WishlistItemUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Reader\WishlistItemReaderInterface
     */
    protected $wishlistItemReader;

    /**
     * @var \Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Facade\ProductConfigurationWishlistsRestApiToWishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @param \Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Reader\WishlistItemReaderInterface $wishlistItemReader
     * @param \Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Facade\ProductConfigurationWishlistsRestApiToWishlistFacadeInterface $wishlistFacade
     */
    public function __construct(
        WishlistItemReaderInterface $wishlistItemReader,
        ProductConfigurationWishlistsRestApiToWishlistFacadeInterface $wishlistFacade
    ) {
        $this->wishlistItemReader = $wishlistItemReader;
        $this->wishlistFacade = $wishlistFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): WishlistItemResponseTransfer {
        $wishlistItemTransfer = $this->wishlistItemReader
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);

        if (!$wishlistItemTransfer) {
            return (new WishlistItemResponseTransfer())->setIsSuccess(false);
        }

        $wishlistItemTransfer = $wishlistItemTransfer
            ->fromArray($wishlistItemRequestTransfer->toArray(), true)
            ->setProductConfigurationInstance($wishlistItemRequestTransfer->getProductConfigurationInstanceOrFail());

        return $this->wishlistFacade->updateWishlistItem($wishlistItemTransfer);
    }
}

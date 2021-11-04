<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi\Business\Deleter;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade\MerchantProductOfferWishlistRestApiToWishlistFacadeInterface;

class MerchantProductOfferWishlistRestApiDeleter implements MerchantProductOfferWishlistRestApiDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade\MerchantProductOfferWishlistRestApiToWishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade\MerchantProductOfferWishlistRestApiToWishlistFacadeInterface $wishlistFacade
     */
    public function __construct(
        MerchantProductOfferWishlistRestApiToWishlistFacadeInterface $wishlistFacade
    ) {
        $this->wishlistFacade = $wishlistFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function deleteWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): void {
        foreach ($wishlistItemTransfers as $wishlistItemTransfer) {
            $uuid = sprintf(
                '%s_%s',
                $wishlistItemTransfer->getSku(),
                $wishlistItemTransfer->getProductOfferReference(),
            );

            if ($wishlistItemRequestTransfer->getUuid() === $uuid) {
                $this->wishlistFacade->removeItem($wishlistItemTransfer);

                break;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function deleteWishlistItemWithoutProductOffer(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): void {
        foreach ($wishlistItemTransfers as $wishlistItemTransfer) {
            if (
                $wishlistItemRequestTransfer->getUuid() === $wishlistItemTransfer->getSku() &&
                !$wishlistItemTransfer->getProductOfferReference()
            ) {
                $this->wishlistFacade->removeItem($wishlistItemTransfer);

                break;
            }
        }
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

class MerchantProductOfferWishlistRestApiReader implements MerchantProductOfferWishlistRestApiReaderInterface
{
    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\WishlistItemTransfer[] $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItemInWishlistItemCollectionByRequest(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): ?WishlistItemTransfer {
        foreach ($wishlistItemTransfers as $wishlistItemTransfer) {
            $uuid = sprintf(
                '%s_%s',
                $wishlistItemTransfer->getSku(),
                $wishlistItemTransfer->getProductOfferReference()
            );

            if ($wishlistItemRequestTransfer->getUuid() === $uuid) {
                return $wishlistItemTransfer;
            }
        }

        return null;
    }
}

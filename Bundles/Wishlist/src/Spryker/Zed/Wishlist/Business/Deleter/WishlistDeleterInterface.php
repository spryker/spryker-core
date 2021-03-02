<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Deleter;

use Generated\Shared\Transfer\WishlistItemCollectionTransfer;

interface WishlistDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollection
     *
     * @return void
     */
    public function deleteItemCollection(WishlistItemCollectionTransfer $wishlistItemTransferCollection): void;
}

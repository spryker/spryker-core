<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Deleter;

use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Wishlist\Persistence\WishlistEntityManagerInterface;

class WishlistDeleter implements WishlistDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistEntityManagerInterface
     */
    protected $wishlistEntityManager;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistEntityManagerInterface $wishlistEntityManager
     */
    public function __construct(WishlistEntityManagerInterface $wishlistEntityManager)
    {
        $this->wishlistEntityManager = $wishlistEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function deleteItemCollection(WishlistItemCollectionTransfer $wishlistItemTransferCollectionTransfer): WishlistItemCollectionTransfer
    {
        $this->handleDatabaseTransaction(function () use ($wishlistItemTransferCollectionTransfer) {
            $this->executeDeleteItemCollectionTransaction($wishlistItemTransferCollectionTransfer);
        });

        return $wishlistItemTransferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollectionTransfer
     *
     * @return void
     */
    protected function executeDeleteItemCollectionTransaction(WishlistItemCollectionTransfer $wishlistItemTransferCollectionTransfer): void
    {
        foreach ($wishlistItemTransferCollectionTransfer->getItems() as $wishlistItemTransfer) {
            $this->wishlistEntityManager->deleteItem($wishlistItemTransfer);
        }
    }
}

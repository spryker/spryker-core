<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface $queryContainer
     */
    public function __construct(WishlistQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        $this->assertWishlistItem($wishlistItemTransfer);

        $entity = $this->queryContainer->queryWishlist()
            ->filterByFkCustomer($wishlistItemTransfer->getFkCustomer())
            ->filterByFkProduct($wishlistItemTransfer->getFkProduct())
            ->findOneOrCreate();

        $entity->save();

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        $this->assertWishlistItem($wishlistItemTransfer);

        $this->queryContainer->queryWishlist()
            ->filterByFkCustomer($wishlistItemTransfer->getFkCustomer())
            ->filterByFkProduct($wishlistItemTransfer->getFkProduct())
            ->delete();

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    protected function assertWishlistItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        $wishlistItemTransfer->requireFkCustomer();
        $wishlistItemTransfer->requireFkProduct();
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\Wishlist;

use Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface;

class WishlistUuidWriter implements WishlistUuidWriterInterface
{
    /**
     * @var \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface
     */
    protected $wishlistsRestApiEntityManager;

    /**
     * @param \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface $wishlistsRestApiEntityManager
     */
    public function __construct(WishlistsRestApiEntityManagerInterface $wishlistsRestApiEntityManager)
    {
        $this->wishlistsRestApiEntityManager = $wishlistsRestApiEntityManager;
    }

    /**
     * @return void
     */
    public function updateWishlistsUuid(): void
    {
        $this->wishlistsRestApiEntityManager->setEmptyWishlistUuids();
    }
}

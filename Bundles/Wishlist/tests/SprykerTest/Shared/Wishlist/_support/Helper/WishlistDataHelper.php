<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Wishlist\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\WishlistBuilder;
use Generated\Shared\DataBuilder\WishlistItemBuilder;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Business\WishlistFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class WishlistDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function haveWishlist(array $override): WishlistTransfer
    {
        $wishlistTransfer = (new WishlistBuilder($override))->build();

        return $this->getWishlistFacade()->createWishlist($wishlistTransfer);
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function haveItemInWishlist(array $override): WishlistItemTransfer
    {
        $wishlistItemTransfer = (new WishlistItemBuilder($override))
            ->build();

        return $this->getWishlistFacade()->addItem($wishlistItemTransfer);
    }

    /**
     * @return \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface
     */
    protected function getWishlistFacade(): WishlistFacadeInterface
    {
        return $this->getLocator()->wishlist()->facade();
    }
}

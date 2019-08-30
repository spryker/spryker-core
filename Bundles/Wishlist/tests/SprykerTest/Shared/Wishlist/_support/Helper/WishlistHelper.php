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
use Orm\Zed\Wishlist\Persistence\Base\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Wishlist\Business\WishlistFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class WishlistHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    //TODO: refactoring of the class: beautify code
    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function haveEmptyWishlist(array $override): WishlistTransfer
    {
        $wishlistTransfer = (new WishlistBuilder($override))
            ->build()
            ->fromArray($override);

        $createdWishlistTransfer = $this->getWishlistFacade()->createWishlist($wishlistTransfer);

        return $createdWishlistTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function haveItemInWishlist(array $override): WishlistItemTransfer
    {
        $wishlistItemTransfer = (new WishlistItemBuilder())
            ->build()
            ->fromArray($override);

        $createdWishlistItemTransfer = $this->getWishlistFacade()->addItem($wishlistItemTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($createdWishlistItemTransfer) {
            if (!$createdWishlistItemTransfer->getIdWishlistItem()) {
                return;
            }
            $this->getWishlistFacade()
                ->removeItem($createdWishlistItemTransfer);
        });

        return $createdWishlistItemTransfer;
    }

    /**
     * @param string $idCustomer
     * @param string $uuidWishlist
     *
     * @return \Orm\Zed\Wishlist\Persistence\Base\SpyWishlist|null
     */
    public function findWishlistEntityDirectlyInDatabase(string $idCustomer, string $uuidWishlist): ?SpyWishlist
    {
        $wishlistEntity = SpyWishlistQuery::create()
            ->filterByUuid($uuidWishlist)
            ->filterByFkCustomer($idCustomer)
            ->findOne();

        return $wishlistEntity;
    }

    /**
     * @return \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface
     */
    protected function getWishlistFacade(): WishlistFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->wishlist()->facade();
    }
}

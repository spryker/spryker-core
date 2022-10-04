<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Wishlist;

use Codeception\Actor;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class WishlistBusinessTester extends Actor
{
    use _generated\WishlistBusinessTesterActions;

    /**
     * @param int $idWishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function getWishlistItemFromPersistence(int $idWishlistItem): WishlistItemTransfer
    {
        $wishlistItemCriteriaTransfer = (new WishlistItemCriteriaTransfer())
            ->setIdWishlistItem($idWishlistItem);

        return $this->getFacade()
            ->getWishlistItem($wishlistItemCriteriaTransfer)
            ->getWishlistItemOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findWishlistByFilter(WishlistFilterTransfer $wishlistFilterTransfer): ?WishlistTransfer
    {
        $wishlistQuery = SpyWishlistQuery::create();

        if ($wishlistFilterTransfer->getIdCustomer()) {
            $wishlistQuery->filterByFkCustomer($wishlistFilterTransfer->getIdCustomer());
        }
        if ($wishlistFilterTransfer->getName()) {
            $wishlistQuery->filterByName($wishlistFilterTransfer->getName());
        }
        $wishlistEntityTransfer = $wishlistQuery->findOne();

        if ($wishlistEntityTransfer) {
            return (new WishlistTransfer())->fromArray($wishlistEntityTransfer->toArray());
        }

        return null;
    }
}

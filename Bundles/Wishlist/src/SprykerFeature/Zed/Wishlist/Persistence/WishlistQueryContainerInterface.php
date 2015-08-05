<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Wishlist\Persistence;

use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItemQuery;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistQuery;

interface WishlistQueryContainerInterface
{
    /**
     * @param integer $idWishlist
     * @param integer $idProduct
     *
     * @return SpyWishlistItemQuery
     */
    public function filterCustomerWishlistByProductId($idWishlist, $idProduct);

    /**
     * @param integer $idWishlist
     * @param string  $groupKey
     *
     * @return SpyWishlistItemQuery
     */
    public function filterCustomerWishlistByGroupKey($idWishlist, $groupKey);

    /**
     * @return SpyWishlistItemQuery
     */
    public function getWishlistItemQuery();

    /**
     * @return SpyWishlistQuery
     */
    public function getWishlistQuery();

    /**
     * @return SpyWishlist
     */
    public function getSpyWishlist();

    /**
     * @return SpyWishlistItem
     */
    public function getSpyWishlistItem();
}

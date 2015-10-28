<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Wishlist\Persistence;

use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;

interface WishlistQueryContainerInterface
{
    /**
     * @param integer $idWishlist
     * @param integer $idProduct
     *
     * @return SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByProductId($idWishlist, $idProduct);

    /**
     * @param integer $idWishlist
     * @param string  $groupKey
     *
     * @return SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByGroupKey($idWishlist, $groupKey);

    /**
     * @return SpyWishlistItemQuery
     */
    public function queryWishlistItem();

    /**
     * @return SpyWishlistQuery
     */
    public function queryWishlist();

}

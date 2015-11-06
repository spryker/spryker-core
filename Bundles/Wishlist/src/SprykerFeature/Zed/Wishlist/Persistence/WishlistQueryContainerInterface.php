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
     * @param int $idWishlist
     * @param int $idProduct
     *
     * @return SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByProductId($idWishlist, $idProduct);

    /**
     * @param int $idWishlist
     * @param string $groupKey
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

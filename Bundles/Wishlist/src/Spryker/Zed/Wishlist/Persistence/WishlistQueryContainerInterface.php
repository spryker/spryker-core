<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Wishlist\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface WishlistQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param int $idWishlist
     * @param int $idProduct
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByProductId($idWishlist, $idProduct);

    /**
     * @param int $idWishlist
     * @param string $groupKey
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByGroupKey($idWishlist, $groupKey);

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryWishlistItem();

    /**
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlist();

}

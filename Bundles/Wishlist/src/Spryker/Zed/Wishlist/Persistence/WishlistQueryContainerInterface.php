<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

interface WishlistQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idWishlist
     * @param int $idProduct
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByProductId($idWishlist, $idProduct);

    /**
     * @api
     *
     * @param int $idWishlist
     * @param string $groupKey
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByGroupKey($idWishlist, $groupKey);

    /**
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery
     */
    public function queryWishlistItem();

    /**
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlist();

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistPersistenceFactory getFactory()
 */
class WishlistQueryContainer extends AbstractQueryContainer implements WishlistQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlist()
    {
        return $this->getFactory()->createWishlistQuery();
    }

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistQuery
     */
    public function queryWishlistByCustomerId($idCustomer)
    {
        return $this->getFactory()
            ->createWishlistQuery()
            ->filterByFkCustomer($idCustomer);
    }

}

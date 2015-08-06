<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\Map\SpyWishlistItemTableMap;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItemQuery;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistQuery;

class WishlistQueryContainer extends AbstractQueryContainer implements WishlistQueryContainerInterface
{
    /**
     * @param integer $idWishlist
     * @param integer $idProduct
     *
     * @return SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByProductId($idWishlist, $idProduct)
    {
        $criteria = new Criteria();
        $criteria->add(SpyWishlistItemTableMap::COL_FK_WISHLIST, $idWishlist)
            ->addAnd(SpyWishlistItemTableMap::COL_FK_PRODUCT, $idProduct);

        return SpyWishlistItemQuery::create(null, $criteria);
    }

    /**
     * @param integer $idWishlist
     * @param string $groupKey
     *
     * @return SpyWishlistItemQuery
     */
    public function queryCustomerWishlistByGroupKey($idWishlist, $groupKey)
    {
        $criteria = new Criteria();
        $criteria->add(SpyWishlistItemTableMap::COL_FK_WISHLIST, $idWishlist);
        $criteria->addAnd(SpyWishlistItemTableMap::COL_GROUP_KEY, $groupKey);

        return SpyWishlistItemQuery::create(null, $criteria);
    }

    /**
     * @return SpyWishlistItemQuery
     */
    public function queryWishlistItem()
    {
        return SpyWishlistItemQuery::create();
    }

    /**
     * @return SpyWishlistQuery
     */
    public function queryWishlist()
    {
        return SpyWishlistQuery::create();
    }
}

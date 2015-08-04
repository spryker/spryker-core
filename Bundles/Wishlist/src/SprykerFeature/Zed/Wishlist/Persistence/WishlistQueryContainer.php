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


class WishlistQueryContainer extends AbstractQueryContainer
{

    /**
     * @param integer $idWishlist
     * @param integer $idAbstractProduct
     * @param integer $idConcreateProduct
     *
     * @return SpyWishlistItemQuery
     */
    public function filterCustomerByConcreteAndAbstractId(
        $idWishlist,
        $idAbstractProduct,
        $idConcreateProduct = null
    ) {
        $criteria = new Criteria();
        $criteria->add(SpyWishlistItemTableMap::COL_FK_WISHLIST, $idWishlist);

        if (isset($idConcreateProduct)) {
            $criteria->addAnd(SpyWishlistItemTableMap::COL_FK_PRODUCT, $idConcreateProduct)
                ->addAnd(SpyWishlistItemTableMap::COL_FK_ABSTRACT_PRODUCT, $idAbstractProduct);
        } else {
            $criteria->addAnd(SpyWishlistItemTableMap::COL_FK_ABSTRACT_PRODUCT, $idAbstractProduct);
        }

        return SpyWishlistItemQuery::create(null, $criteria);
    }

    /**
     * @param integer $idWishlist
     * @param string $groupKey
     *
     * @return SpyWishlistItemQuery
     */
    public function filterCustomerByGroupKey($idWishlist, $groupKey)
    {
        $criteria = new Criteria();
        $criteria->add(SpyWishlistItemTableMap::COL_FK_WISHLIST, $idWishlist);
        $criteria->addAnd(SpyWishlistItemTableMap::COL_GROUP_KEY, $groupKey);

        return SpyWishlistItemQuery::create(null, $criteria);
    }

    /**
     * @return SpyWishlistItemQuery
     */
    public function getWishlistItemQuery()
    {
        return SpyWishlistItemQuery::create();
    }

    /**
     * @return SpyWishlistQuery
     */
    public function getWishlistQuery()
    {
        return SpyWishlistQuery::create();
    }
}

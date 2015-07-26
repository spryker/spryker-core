<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Generated\Shared\Wishlist\WishlistItemInterface;
use Generated\Shared\Customer\CustomerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\Map\SpyWishlistItemTableMap;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItemQuery;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Join;

class WishlistQueryContainer extends AbstractQueryContainer
{
    const ABSTRACT_SKU_COL_NAME = "abstract_sku";

    const CONCRETE_SKU_COL_NAME = "concrete_sku";

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return SpyWishlist
     */
    public function queryCustomerWishlist(CustomerInterface $customerTransfer)
    {
        $wishlist = SpyWishlistQuery::create()
            ->findOneByFkCustomer($customerTransfer->getIdCustomer());

        return $wishlist;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return array
     */
    public function queryCustomerWishlistItemsArray(CustomerInterface $customerTransfer)
    {
        return SpyWishlistItemQuery::create()
            ->useSpyWishlistQuery('w')
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->endUse()
            ->addJoinObject(
                new Join(
                    SpyWishlistItemTableMap::COL_FK_CONCRETE_PRODUCT,
                    SpyProductTableMap::COL_ID_PRODUCT,
                    Criteria::LEFT_JOIN

                ), 'c')
            ->addJoinObject(
                new Join(
                    SpyProductTableMap::COL_FK_ABSTRACT_PRODUCT,
                    SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                    Criteria::LEFT_JOIN

                ), 'a')
            ->withColumn(
                spyAbstractProductTableMap::COL_SKU,
                self::ABSTRACT_SKU_COL_NAME
            )
            ->withColumn(
                SpyProductTableMap::COL_SKU,
                self::CONCRETE_SKU_COL_NAME
            )
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return SpyProduct
     */
    public function queryConcreteProduct(WishlistItemInterface $wishlistItemTransfer)
    {
        $concreteProduct = SpyProductQuery::create()
            ->findOneBySku($wishlistItemTransfer->getProduct()->getConcreteSku());

        return $concreteProduct;
    }

    /**
     * @return SpyWishlistItemQuery
     */
    public function getWishlistItemQuery()
    {
        return SpyWishlistItemQuery::create();
    }

    /**
     * @param WishlistItemInterface $wishlistTransfer
     *
     * @return SpyWishlistItemQuery
     */
    public function filterWishlistItemQueryByPrimaryKey(WishlistItemInterface $wishlistTransfer)
    {
        return $this->getWishlistItemQuery()
            ->filterByPrimaryKey($wishlistTransfer->getId());

    }


}

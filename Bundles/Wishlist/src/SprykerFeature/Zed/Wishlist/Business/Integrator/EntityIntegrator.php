<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Integrator;

use Generated\Shared\CustomerCheckoutConnector\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

class EntityIntegrator
{
    protected $queryContainer;

    /**
     * @param WishlistQueryContainer $queryContainer
     */
    public function __construct(WishlistQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return string
     */
    public function getConcreteSkuColumnName()
    {
        $queryContainer = $this->queryContainer;
        return $queryContainer::CONCRETE_SKU_COL_NAME;
    }

    /**
     * @return string
     */
    public function getAbstractSkuColumnName()
    {
        $queryContainer = $this->queryContainer;
        return $queryContainer::ABSTRACT_SKU_COL_NAME;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return array
     */
    public function getItems(CustomerInterface $customerTransfer)
    {
        return $this->queryContainer
                    ->queryCustomerWishlistItemsArray($customerTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function saveItems(WishlistChangeInterface $changeTransfer)
    {
        $idWishlist = $this->getCustomerIdWishlist($changeTransfer->getCustomer());

        foreach ($changeTransfer->getItems() as $itemTransfer) {

            $idProduct = $this->getWishlistItemIdProduct($itemTransfer);

            $item = (new SpyWishlistItem())
                ->setFkWishlist($idWishlist)
                ->setFkConcreteProduct($idProduct)
                ->setQuantity($itemTransfer->getQuantity())
                ->setAddedAt($itemTransfer->getAddedAt());

            if(null !== $itemTransfer->getId()) {
                $item->setPrimaryKey($itemTransfer->getId());
            }

            $item->save();
        }
    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return int
     */
    public function removeItem(WishlistItemInterface $wishlistItemTransfer)
    {
        return $this->queryContainer
             ->filterWishlistItemQueryByPrimaryKey($wishlistItemTransfer)
             ->delete();
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return bool
     */
    protected function customerHasWishlist(CustomerInterface $customerTransfer)
    {
        $instance = $this->queryContainer->queryCustomerWishlist($customerTransfer);
        return ($instance instanceof SpyWishlist);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return int
     */
    protected function getCustomerIdWishlist(CustomerInterface $customerTransfer)
    {
        if(!$this->customerHasWishlist($customerTransfer)) {
            $this->createCustomerWishlist($customerTransfer);
        }

        return $this->queryContainer
                    ->queryCustomerWishlist($customerTransfer)
                    ->getIdWishlist();

    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return int
     */
    protected function getWishlistItemIdProduct(WishlistItemInterface $wishlistItemTransfer)
    {
        $concreteProduct = $this->queryContainer->queryConcreteProduct($wishlistItemTransfer);
        return $concreteProduct->getIdProduct();
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return int
     */
    protected function createCustomerWishlist(CustomerInterface $customerTransfer)
    {
       return (new SpyWishlist())
           ->setFkCustomer($customerTransfer->getIdCustomer())#
           ->save();
    }
}

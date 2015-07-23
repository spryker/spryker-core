<?php

namespace SprykerFeature\Zed\Wishlist\Business;



use Generated\Shared\CustomerCheckoutConnector\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;


class EntityManager
{
    protected $queryContainer;


    public function __construct(WishlistQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    public function getConcreteSkuColumnName()
    {
        $queryContainer = $this->queryContainer;
        return $queryContainer::CONCRETE_SKU_COL_NAME;
    }

    public function getAbstractSkuColumnName()
    {
        $queryContainer = $this->queryContainer;
        return $queryContainer::ABSTRACT_SKU_COL_NAME;
    }


    public function getItems(CustomerInterface $customerTransfer)
    {
        return $this->queryContainer
                    ->queryCustomerWishlistItemsArray($customerTransfer);

    }


    public function saveItems(WishlistChangeInterface $changeTransfer)
    {
        $idWishlist = $this->getCustomerIdWishlist($changeTransfer->getCustomer());

        foreach ($changeTransfer->getItems() as $itemTransfer) {
            $idProduct = $this->getWishlistItemIdProduct($itemTransfer);
            (new SpyWishlistItem())
                ->setFkWishlist($idWishlist)
                ->setFkConcreteProduct($idProduct)
                ->setQuantity($itemTransfer->getQuantity())
                ->setAddedAt($itemTransfer->getAddedAt())
                ->save();
        }
    }

    protected function customerHasWishlist(CustomerInterface $customerTransfer)
    {
        $instance = $this->queryContainer->queryCustomerWishlist($customerTransfer);
        return ($instance instanceof SpyWishlist);
    }

    protected function getCustomerIdWishlist(CustomerInterface $customerTransfer)
    {
        if(!$this->customerHasWishlist($customerTransfer)) {
            $this->createCustomerWishlist($customerTransfer);
        }

        return $this->queryContainer
                    ->queryCustomerWishlist($customerTransfer)
                    ->getIdWishlist();

    }

    protected function getWishlistItemIdProduct(WishlistItemInterface $wishlistItemTransfer)
    {
        $concreteProduct = $this->queryContainer->queryConcreteProduct($wishlistItemTransfer);
        return $concreteProduct->getIdProduct();
    }

    protected function createCustomerWishlist(CustomerInterface $customerTransfer)
    {
       return (new SpyWishlist())
           ->setFkCustomer($customerTransfer->getIdCustomer())#
           ->save();
    }
}

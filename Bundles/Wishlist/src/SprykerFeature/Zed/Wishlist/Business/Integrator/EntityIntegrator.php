<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Integrator;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;

class EntityIntegrator
{
    /**
     * @var WishlistQueryContainer
     */
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

        foreach ($changeTransfer->getAddedItems() as $itemTransfer) {

            $idProduct = $this->getWishlistItemIdProduct($itemTransfer);


            if (null !== $itemTransfer->getId()) {

                $item = $this->queryContainer->queryWishlistItemByIdWishlistItem($itemTransfer->getId());
            }
            else {
                $item = (new SpyWishlistItem())
                    ->setAddedAt($itemTransfer->getAddedAt())
                    ->setFkWishlist($idWishlist)
                    ->setFkConcreteProduct($idProduct);
            }

            $item->setQuantity($itemTransfer->getQuantity());
            $item->save();
        }
    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     */
    public function removeItems(WishlistChangeInterface $changeTransfer)
    {
        foreach ($changeTransfer->getRemovedItems() as $item) {
            $this->queryContainer
                ->filterWishlistItemQueryByPrimaryKey($item)
                ->delete();
        }

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
        if (!$this->customerHasWishlist($customerTransfer)) {
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

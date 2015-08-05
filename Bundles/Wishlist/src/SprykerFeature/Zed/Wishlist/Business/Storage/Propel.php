<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlist;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainer;
use SprykerFeature\Zed\Wishlist\Business\Model\Customer;

class Propel extends BaseStorage implements StorageInterface
{
    /**
     * @var WishlistQueryContainer
     */
    protected $wishlistQueryContainer;

    /**
     * @var SpyWishlist
     */
    protected $spyWishlist;

    /**
     * @var CustomerInterface
     */
    protected $customerTransfer;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @param WishlistQueryContainer $wishlistQueryContainer
     * @param Customer               $customer
     * @param WishlistInterface      $wishlist
     * @param CustomerInterface      $customerTransfer
     * @param ProductFacade          $productFacade
     */
    public function __construct(
        WishlistQueryContainer $wishlistQueryContainer,
        Customer $customer,
        WishlistInterface $wishlist,
        CustomerInterface $customerTransfer,
        ProductFacade $productFacade
    ) {
        parent::__construct($wishlist);

        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
        $this->wishlist = $wishlist;
        $this->customer = $customer;
        $this->productFacade = $productFacade;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistChangeInterface
     */
    public function addItems(WishlistChangeInterface $wishlistChange)
    {
        foreach ($wishlistChange->getItems() as $item) {
            $spyWishlistItem = $this->getSpyWishlistItem($item);

            if (empty($spyWishlistItem)) {
                $this->createNewWishlistItem($item);
            } else {
                $spyWishlistItem->setQuantity($spyWishlistItem->getQuantity() + $item->getQuantity());
                $spyWishlistItem->save();
            }
        }

        $wishlist = $this->customer->getWishlist();

        return $wishlist;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function removeItems(WishlistChangeInterface $wishlistChange)
    {
        $wishlistItems = $wishlistChange->getItems();
        foreach ($wishlistItems as $wishlistItem) {
            $spyWishlistItem = $this->getSpyWishlistItem($wishlistItem);

            if (empty($spyWishlistItem)) {
                continue;
            }

            $quantityDifference = $spyWishlistItem->getQuantity() - $wishlistItem->getQuantity();
            if ($quantityDifference <= 1 || $wishlistItem->getQuantity() === 0) {
                $spyWishlistItem->delete();
            } else {
                $spyWishlistItem->setQuantity($quantityDifference);
                $spyWishlistItem->save();
            }
        }

        $wishlist = $this->customer->getWishlist();

        return $wishlist;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function increaseItems(WishlistChangeInterface $wishlistChange)
    {
        return $this->addItems($wishlistChange);
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function decreaseItems(WishlistChangeInterface $wishlistChange)
    {
        return $this->removeItems($wishlistChange);
    }

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return int
     */
    protected function createNewWishlistItem(ItemInterface $wishlistItem)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $spyWishList = $this->getSpyWishlist($idCustomer);

        if (empty($spyWishList)) {
            $spyWishList = $this->createSpyWishlist($idCustomer);
        }

        $spyWishlistItem = new SpyWishlistItem();
        $spyWishlistItem->setGroupKey($wishlistItem->getGroupKey());

        $concreteProduct = $this->productFacade->getConcreteProduct($wishlistItem->getSku());

        $spyWishlistItem->setFkProduct($concreteProduct->getIdConcreteProduct());
        $spyWishlistItem->setFkAbstractProduct($concreteProduct->getIdAbstractProduct());

        $spyWishlistItem->setFkWishlist($spyWishList->getIdWishlist());
        $spyWishlistItem->setQuantity($wishlistItem->getQuantity());
        $spyWishlistItem->setAddedAt(new \DateTime());

        return $spyWishlistItem->save();
    }

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return SpyWishlistItem
     */
    protected function getSpyWishlistItem(ItemInterface $wishlistItem)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $spyWishlist = $this->getSpyWishlist($idCustomer);

        if (empty($spyWishlist)) {
            return null;
        }

        $spyWishlistItem = null;
        if (!empty($wishlistItem->getGroupKey())) {
            $spyWishlistItem = $this->wishlistQueryContainer
                ->filterCustomerByGroupKey($spyWishlist->getIdWishlist(), $wishlistItem->getGroupKey())
                ->findOne();

        }

        if (empty($spyWishlistItem)) {
            $idConcreateProduct = $this->productFacade->getConcreteProductIdBySku($wishlistItem->getSku());
            $spyWishlistItem = $this->wishlistQueryContainer
                ->filterCustomerByProductId(
                    $spyWishlist->getIdWishlist(),
                    $idConcreateProduct
                )->findOne();

        }

        return $spyWishlistItem;
    }

    /**
     * @param integer $idCustomer
     *
     * @return SpyWishlist
     */
    protected function getSpyWishlist($idCustomer)
    {
        if (null === $this->spyWishlist) {
            $this->spyWishlist = $this->wishlistQueryContainer
                ->getWishlistQuery()
                ->findOneByFkCustomer($idCustomer);
        }

        return $this->spyWishlist;
    }

    /**
     * @param integer $idCustomer
     *
     * @return SpyWishlist
     */
    protected function createSpyWishlist($idCustomer)
    {
        $spyWishlist = new SpyWishlist();
        $spyWishlist->setFkCustomer($idCustomer);
        $spyWishlist->save();

        return $spyWishlist;
    }

}

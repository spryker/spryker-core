<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Product\ConcreteProductInterface;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Business\Model\Customer;
use SprykerFeature\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Propel implements StorageInterface
{
    /**
     * @var WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

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
    protected $facadeProduct;

    /**
     * @param WishlistQueryContainerInterface $wishlistQueryContainer
     * @param Customer               $customer
     * @param WishlistInterface      $wishlistTransfer
     * @param CustomerInterface      $customerTransfer
     * @param ProductFacade          $facadeProduct
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        Customer $customer,
        WishlistInterface $wishlistTransfer,
        CustomerInterface $customerTransfer,
        ProductFacade $facadeProduct
    ) {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
        $this->wishlistTransfer = $wishlistTransfer;
        $this->customer = $customer;
        $this->facadeProduct = $facadeProduct;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistChangeInterface
     */
    public function addItems(WishlistChangeInterface $wishlistChange)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $wishlistEntity = $this->getWishlistEntity($idCustomer);

        if (empty($wishlistEntity)) {
            $wishlistEntity = $this->createWishlistEntity($idCustomer);
        }

        foreach ($wishlistChange->getItems() as $wishlistItemTransfer) {
            $wishlistItemEntity = $this->getWishlistItemEntity($wishlistItemTransfer, $wishlistEntity->getIdWishlist());

            if (empty($wishlistItemEntity)) {
                $concreteProductTransfer = $this->facadeProduct->getConcreteProduct($wishlistItemTransfer->getSku());

                $this->createNewWishlistItem(
                    $wishlistItemTransfer,
                    $wishlistEntity->getIdWishlist(),
                    $concreteProductTransfer
                );
            } else {
                $this->updateWishlistItem($wishlistItemEntity, $wishlistItemTransfer);
            }
        }

        $wishlistTransfer = $this->customer->getWishlist();

        return $wishlistTransfer;
    }

    /**
     * @param WishlistChangeInterface $wishlistChange
     *
     * @return WishlistInterface
     */
    public function removeItems(WishlistChangeInterface $wishlistChange)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $wishlistEntity = $this->getWishlistEntity($idCustomer);

        $wishlistItems = $wishlistChange->getItems();
        foreach ($wishlistItems as $wishlistItemTransfer) {
            $wishlistItemEntity = $this->getWishlistItemEntity($wishlistItemTransfer, $wishlistEntity->getIdWishlist());

            if (empty($wishlistItemEntity)) {
                continue;
            }

            $quantityDifference = $wishlistItemEntity->getQuantity() - $wishlistItemTransfer->getQuantity();
            if ($quantityDifference <= 0) {
                $this->deleteWishlistItem($wishlistItemEntity);
            } else {
                $wishlistItemEntity->setQuantity($quantityDifference);
                $wishlistItemEntity->save();
            }
        }

        $wishlistTransfer = $this->customer->getWishlist();

        return $wishlistTransfer;
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
     * @param ItemInterface $wishlistItemTransfer
     * @param integer       $idWishlist
     * @param ConcreteProductInterface $concreteProductTransfer
     *
     * @return SpyWishlistItem
     */
    protected function createNewWishlistItem(
        ItemInterface $wishlistItemTransfer,
        $idWishlist,
        ConcreteProductInterface $concreteProductTransfer
    ) {
        $wishlistItemEntity = new SpyWishlistItem();
        $wishlistItemEntity->setGroupKey($wishlistItemTransfer->getGroupKey());
        $wishlistItemEntity->setFkProduct($concreteProductTransfer->getIdConcreteProduct());
        $wishlistItemEntity->setFkAbstractProduct($concreteProductTransfer->getIdAbstractProduct());
        $wishlistItemEntity->setFkWishlist($idWishlist);
        $wishlistItemEntity->setQuantity($wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->setAddedAt(new \DateTime());
        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @param ItemInterface $wishlistItemTransfer
     * @param integer $idWishlist
     *
     * @return null|SpyWishlistItem
     */
    protected function getWishlistItemEntity(ItemInterface $wishlistItemTransfer, $idWishlist)
    {
        $wishlistItemEntity = null;
        if (!empty($wishlistItemTransfer->getGroupKey())) {
            $wishlistItemEntity = $this->wishlistQueryContainer
                ->queryCustomerWishlistByGroupKey($idWishlist, $wishlistItemTransfer->getGroupKey())
                ->findOne();

        }

        if (empty($wishlistItemEntity)) {
            $idConcreteProduct = $this->facadeProduct->getConcreteProductIdBySku($wishlistItemTransfer->getSku());
            $wishlistItemEntity = $this->wishlistQueryContainer
                ->queryCustomerWishlistByProductId($idWishlist, $idConcreteProduct)
                ->findOne();
        }

        return $wishlistItemEntity;
    }

    /**
     * @param integer $idCustomer
     *
     * @return SpyWishlist
     */
    protected function createWishlistEntity($idCustomer)
    {
        $wishlistEntity = new SpyWishlist();
        $wishlistEntity->setFkCustomer($idCustomer);
        $wishlistEntity->save();

        return $wishlistEntity;
    }

    /**
     * @param integer $idCustomer
     *
     * @return SpyWishlist
     */
    protected function getWishlistEntity($idCustomer)
    {
        $wishlistEntity = $this->wishlistQueryContainer
            ->queryWishlist()
            ->findOneByFkCustomer($idCustomer);

        return $wishlistEntity;
    }

    /**
     * @param SpyWishlistItem $wishlistItemEntity
     */
    protected function deleteWishlistItem(SpyWishlistItem $wishlistItemEntity)
    {
        $wishlistItemEntity->delete();
    }

    /**
     * @param SpyWishlistItem $wishlistItemEntity
     * @param ItemTransfer $wishlistItemTransfer
     */
    protected function updateWishlistItem(SpyWishlistItem $wishlistItemEntity, ItemTransfer $wishlistItemTransfer)
    {
        $wishlistItemEntity->setQuantity($wishlistItemEntity->getQuantity() + $wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->save();
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Product\Business\ProductFacade;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Spryker\Zed\Wishlist\Business\Model\Customer;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Propel implements StorageInterface
{

    /**
     * @var WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var WishlistTransfer
     */
    protected $wishlistTransfer;

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
     * @param Customer $customer
     * @param WishlistTransfer $wishlistTransfer
     * @param CustomerTransfer $customerTransfer
     * @param ProductFacade $facadeProduct
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        Customer $customer,
        WishlistTransfer $wishlistTransfer,
        CustomerTransfer $customerTransfer,
        ProductFacade $facadeProduct
    ) {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
        $this->wishlistTransfer = $wishlistTransfer;
        $this->customer = $customer;
        $this->facadeProduct = $facadeProduct;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistChangeTransfer
     */
    public function addItems(WishlistChangeTransfer $wishlistChange)
    {
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $wishlistEntity = $this->getWishlistEntity($idCustomer);

        if (empty($wishlistEntity)) {
            $wishlistEntity = $this->createWishlistEntity($idCustomer);
        }

        foreach ($wishlistChange->getItems() as $wishlistItemTransfer) {
            $wishlistItemEntity = $this->getWishlistItemEntity($wishlistItemTransfer, $wishlistEntity->getIdWishlist());

            if (empty($wishlistItemEntity)) {
                $productConcreteTransfer = $this->facadeProduct->getProductConcrete($wishlistItemTransfer->getSku());

                $this->createNewWishlistItem(
                    $wishlistItemTransfer,
                    $wishlistEntity->getIdWishlist(),
                    $productConcreteTransfer
                );
            } else {
                $this->updateWishlistItem($wishlistItemEntity, $wishlistItemTransfer);
            }
        }

        $wishlistTransfer = $this->customer->getWishlist();

        return $wishlistTransfer;
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItems(WishlistChangeTransfer $wishlistChange)
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
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseItems(WishlistChangeTransfer $wishlistChange)
    {
        return $this->addItems($wishlistChange);
    }

    /**
     * @param WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseItems(WishlistChangeTransfer $wishlistChange)
    {
        return $this->removeItems($wishlistChange);
    }

    /**
     * @param ItemTransfer $wishlistItemTransfer
     * @param int $idWishlist
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItem
     */
    protected function createNewWishlistItem(
        ItemTransfer $wishlistItemTransfer,
        $idWishlist,
        ProductConcreteTransfer $productConcreteTransfer
    ) {
        $wishlistItemEntity = new SpyWishlistItem();
        $wishlistItemEntity->setGroupKey($wishlistItemTransfer->getGroupKey());
        $wishlistItemEntity->setFkProduct($productConcreteTransfer->getIdProductConcrete());
        $wishlistItemEntity->setFkProductAbstract($productConcreteTransfer->getIdProductAbstract());
        $wishlistItemEntity->setFkWishlist($idWishlist);
        $wishlistItemEntity->setQuantity($wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->setAddedAt(new \DateTime());
        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @param ItemTransfer $wishlistItemTransfer
     * @param int $idWishlist
     *
     * @return SpyWishlistItem|null
     */
    protected function getWishlistItemEntity(ItemTransfer $wishlistItemTransfer, $idWishlist)
    {
        $wishlistItemEntity = null;
        if (!empty($wishlistItemTransfer->getGroupKey())) {
            $wishlistItemEntity = $this->wishlistQueryContainer
                ->queryCustomerWishlistByGroupKey($idWishlist, $wishlistItemTransfer->getGroupKey())
                ->findOne();
        }

        if (empty($wishlistItemEntity)) {
            $idProductConcrete = $this->facadeProduct->getProductConcreteIdBySku($wishlistItemTransfer->getSku());
            $wishlistItemEntity = $this->wishlistQueryContainer
                ->queryCustomerWishlistByProductId($idWishlist, $idProductConcrete)
                ->findOne();
        }

        return $wishlistItemEntity;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlist
     */
    protected function createWishlistEntity($idCustomer)
    {
        $wishlistEntity = new SpyWishlist();
        $wishlistEntity->setFkCustomer($idCustomer);
        $wishlistEntity->save();

        return $wishlistEntity;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlist
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
     *
     * @return void
     */
    protected function deleteWishlistItem(SpyWishlistItem $wishlistItemEntity)
    {
        $wishlistItemEntity->delete();
    }

    /**
     * @param SpyWishlistItem $wishlistItemEntity
     * @param ItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    protected function updateWishlistItem(SpyWishlistItem $wishlistItemEntity, ItemTransfer $wishlistItemTransfer)
    {
        $wishlistItemEntity->setQuantity($wishlistItemEntity->getQuantity() + $wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->save();
    }

}

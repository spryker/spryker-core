<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Spryker\Zed\Wishlist\Business\Model\CustomerInterface;
use Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductInterface;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Propel implements StorageInterface
{

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlistTransfer;

    /**
     * @var \Spryker\Zed\Wishlist\Business\Model\CustomerInterface
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductInterface
     */
    protected $facadeProduct;

    /**
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface $wishlistQueryContainer
     * @param \Spryker\Zed\Wishlist\Business\Model\CustomerInterface $customer
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Zed\Wishlist\Dependency\Facade\WishlistToProductInterface $facadeProduct
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        CustomerInterface $customer,
        WishlistTransfer $wishlistTransfer,
        CustomerTransfer $customerTransfer,
        WishlistToProductInterface $facadeProduct
    ) {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
        $this->wishlistTransfer = $wishlistTransfer;
        $this->customer = $customer;
        $this->facadeProduct = $facadeProduct;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
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
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
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
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseItems(WishlistChangeTransfer $wishlistChange)
    {
        return $this->addItems($wishlistChange);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseItems(WishlistChangeTransfer $wishlistChange)
    {
        return $this->removeItems($wishlistChange);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItemTransfer
     * @param int $idWishlist
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
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
        $wishlistItemEntity->setFkProductAbstract($productConcreteTransfer->getFkProductAbstract());
        $wishlistItemEntity->setFkWishlist($idWishlist);
        $wishlistItemEntity->setQuantity($wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->setAddedAt(new \DateTime());
        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItemTransfer
     * @param int $idWishlist
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItem|null
     */
    protected function getWishlistItemEntity(ItemTransfer $wishlistItemTransfer, $idWishlist)
    {
        $wishlistItemEntity = null;
        if ($wishlistItemTransfer->getGroupKey()) {
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
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem $wishlistItemEntity
     *
     * @return void
     */
    protected function deleteWishlistItem(SpyWishlistItem $wishlistItemEntity)
    {
        $wishlistItemEntity->delete();
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem $wishlistItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItemTransfer
     *
     * @return void
     */
    protected function updateWishlistItem(SpyWishlistItem $wishlistItemEntity, ItemTransfer $wishlistItemTransfer)
    {
        $wishlistItemEntity->setQuantity($wishlistItemEntity->getQuantity() + $wishlistItemTransfer->getQuantity());
        $wishlistItemEntity->save();
    }

}

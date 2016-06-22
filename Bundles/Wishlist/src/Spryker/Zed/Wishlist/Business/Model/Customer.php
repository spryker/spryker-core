<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface;

class Customer implements CustomerInterface
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
     * @param \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface $wishlistQueryContainer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     */
    public function __construct(
        WishlistQueryContainerInterface $wishlistQueryContainer,
        CustomerTransfer $customerTransfer
    ) {
        $this->wishlistQueryContainer = $wishlistQueryContainer;
        $this->customerTransfer = $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist()
    {
        $wishlist = $this->wishlistQueryContainer
            ->queryWishlist()
            ->findOneByFkCustomer($this->customerTransfer->getIdCustomer());

        $wishlistTransfer = new WishlistTransfer();
        foreach ($wishlist->getSpyWishlistItems() as $wishlistItemEntity) {
            $wishlistItemTransfer = new ItemTransfer();
            $productEntity = $wishlistItemEntity->getSpyProduct();
            $wishlistItemTransfer->setGroupKey($wishlistItemEntity->getGroupKey())
                ->setAddedAt($wishlistItemEntity->getAddedAt())
                ->setIdProductAbstract($wishlistItemEntity->getFkProductAbstract())
                ->setSku($productEntity->getSku())
                ->setQuantity($wishlistItemEntity->getQuantity());

            $wishlistTransfer->addItem($wishlistItemTransfer);
        }

        return $wishlistTransfer;
    }

}

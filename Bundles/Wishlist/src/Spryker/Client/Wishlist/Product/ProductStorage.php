<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Product;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface;

class ProductStorage implements ProductStorageInterface
{

    /**
     * @var \Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface
     */
    protected $productClient;

    /**
     * @param \Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface $productClient
     */
    public function __construct(WishlistToProductInterface $productClient)
    {
        $this->productClient = $productClient;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function expandProductDetails(WishlistOverviewResponseTransfer $wishlistResponseTransfer)
    {
        $wishlistResponseTransfer->requireWishlist();

        $ids = [];
        foreach ($wishlistResponseTransfer->getItems() as $wishlistItem) {
            $ids[] = $wishlistItem->getIdProduct();
        }

        if (empty($ids)) {
            return $wishlistResponseTransfer;
        }

        $wishlistResponseTransfer->setItems(new \ArrayObject());

        $storageProductCollection = $this->productClient->getProductConcreteCollection($ids);

        foreach ($storageProductCollection as $storageProduct) {
            $wishlistItem = (new WishlistItemTransfer())
                ->setIdProduct($storageProduct->getId())
                ->setFkWishlist($wishlistResponseTransfer->getWishlist()->getIdWishlist())
                ->setProduct($storageProduct);

            $wishlistResponseTransfer->addItem($wishlistItem);
        }

        return $wishlistResponseTransfer;
    }

}

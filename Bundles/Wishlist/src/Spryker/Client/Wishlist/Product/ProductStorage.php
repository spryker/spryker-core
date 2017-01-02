<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Product;

use ArrayObject;
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

        $idProductCollection = $this->getIdProductCollection($wishlistResponseTransfer);
        if (empty($idProductCollection)) {
            return $wishlistResponseTransfer;
        }

        $wishlistResponseTransfer->setItems(new ArrayObject());

        $storageProductCollection = $this->productClient->getProductConcreteCollection($idProductCollection);
        foreach ($storageProductCollection as $storageProduct) {
            $wishlistItem = (new WishlistItemTransfer())
                ->setIdProduct($storageProduct->getIdProductConcrete())
                ->setFkWishlist($wishlistResponseTransfer->getWishlist()->getIdWishlist())
                ->setProduct($storageProduct);

            $wishlistResponseTransfer->addItem($wishlistItem);
        }

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistResponseTransfer
     *
     * @return array
     */
    protected function getIdProductCollection(WishlistOverviewResponseTransfer $wishlistResponseTransfer)
    {
        $idProductCollection = [];
        foreach ($wishlistResponseTransfer->getItems() as $wishlistItem) {
            $idProductCollection[] = $wishlistItem->getIdProduct();
        }

        return $idProductCollection;
    }

}

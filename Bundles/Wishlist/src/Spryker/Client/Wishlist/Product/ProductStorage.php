<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Product;

use ArrayObject;
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

        $validWishlistItems = $this->getValidWishlistItems($wishlistResponseTransfer, $idProductCollection);
        $wishlistResponseTransfer->setItems($validWishlistItems);

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

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistResponseTransfer
     * @param array $idProductCollection
     *
     * @return \ArrayObject
     */
    protected function getValidWishlistItems(WishlistOverviewResponseTransfer $wishlistResponseTransfer, array $idProductCollection)
    {
        $validWishlistItems = new ArrayObject();

        $storageProductCollection = $this->getStorageProductCollection($idProductCollection);
        foreach ($wishlistResponseTransfer->getItems() as $wishlistItemTransfer) {
            if (!array_key_exists($wishlistItemTransfer->getIdProduct(), $storageProductCollection)) {
                continue;
            }

            $wishlistItemTransfer->setProduct($storageProductCollection[$wishlistItemTransfer->getIdProduct()]);
            $validWishlistItems->append($wishlistItemTransfer);
        }

        return $validWishlistItems;
    }

    /**
     * @param array $idProductCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    protected function getStorageProductCollection(array $idProductCollection)
    {
        $result = [];
        $storageProductCollection = $this->productClient->getProductConcreteCollection($idProductCollection);

        foreach ($storageProductCollection as $storageProductTransfer) {
            $result[$storageProductTransfer->getIdProductConcrete()] = $storageProductTransfer;
        }

        return $result;
    }
}

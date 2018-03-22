<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Product;

use ArrayObject;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToPriceProductClientInterface;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface;

class ProductStorage implements ProductStorageInterface
{
    /**
     * @var \Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface
     */
    protected $productClient;

    /**
     * @var \Spryker\Client\Wishlist\Dependency\Client\WishlistToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\Wishlist\Dependency\Client\WishlistToProductInterface $productClient
     * @param \Spryker\Client\Wishlist\Dependency\Client\WishlistToPriceProductClientInterface $priceProductClient
     */
    public function __construct(
        WishlistToProductInterface $productClient,
        WishlistToPriceProductClientInterface $priceProductClient
    ) {
        $this->productClient = $productClient;
        $this->priceProductClient = $priceProductClient;
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
            $currentPriceTransfer = $this->priceProductClient->resolveProductPrice($storageProductTransfer->getPrices());

            $storageProductTransfer->setPrice($currentPriceTransfer->getPrice());
            $storageProductTransfer->setPrices($currentPriceTransfer->getPrices());

            $result[$storageProductTransfer->getIdProductConcrete()] = $storageProductTransfer;
        }

        return $result;
    }
}

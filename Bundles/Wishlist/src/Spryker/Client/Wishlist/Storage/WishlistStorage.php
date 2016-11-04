<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Storage;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Client\Storage\StorageClientInterface;

class WishlistStorage implements WishlistStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    protected $productClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Client\Product\ProductClientInterface $productClient
     */
    public function __construct(StorageClientInterface $storageClient, ProductClientInterface $productClient)
    {
        $this->storageClient = $storageClient;
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
            $ids[] = $wishlistItem->getFkProduct();
        }

        if (empty($ids)) {
            return $wishlistResponseTransfer;
        }

        $wishlistResponseTransfer->setItems(new \ArrayObject());

        $storageProductCollection = $this->productClient->getProductConcreteCollection($ids);

        foreach ($storageProductCollection as $storageProduct) {
            $wishlistItem = (new WishlistItemTransfer())
                ->setFkProduct($storageProduct->getId())
                ->setFkWishlist($wishlistResponseTransfer->getWishlist()->getIdWishlist())
                ->setProduct($storageProduct);

            $wishlistResponseTransfer->addItem($wishlistItem);
        }

        return $wishlistResponseTransfer;
    }

}

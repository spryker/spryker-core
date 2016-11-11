<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Storage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Client\Storage\StorageClientInterface;

class WishlistStorage implements WishlistStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $storageClient;

    /**
     * @var \Spryker\Client\Product\ProductClientInterface
     */
    private $productClient;

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
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlist
     *
     * @return void
     */
    public function expandProductDetails(WishlistTransfer $wishlist)
    {
        foreach ($wishlist->getItems() as $item) {
            $productData = $this
                ->productClient
                ->getProductAbstractFromStorageByIdForCurrentLocale($item->getIdProductAbstract());

            foreach ($productData['product_concrete_collection'] as $product) {
                if ($product['sku'] !== $item->getSku()) {
                    continue;
                }
                $productConcrete = new ProductConcreteTransfer();
                $productConcrete->setName($product['name']);
                $productConcrete->setSku($product['sku']);
                $productConcrete->setAttributes($product['attributes']);
                $item->setProductConcrete($productConcrete);
            }
        }
    }

}

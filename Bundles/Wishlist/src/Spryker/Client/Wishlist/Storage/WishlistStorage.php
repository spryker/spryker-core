<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Wishlist\Storage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Client\Storage\StorageClientInterface;

class WishlistStorage implements WishlistStorageInterface
{

    /**
     * @var StorageClientInterface
     */
    private $storageClient;

    /**
     * @var ProductClientInterface
     */
    private $productClient;

    /**
     * @param StorageClientInterface $storageClient
     * @param ProductClientInterface $productClient
     */
    public function __construct(StorageClientInterface $storageClient, ProductClientInterface $productClient)
    {
        $this->storageClient = $storageClient;
        $this->productClient = $productClient;
    }

    /**
     * @param WishlistTransfer $wishlist
     *
     * @return WishlistTransfer
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

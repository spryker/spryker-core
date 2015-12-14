<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Storage;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerFeature\Client\Product\ProductClientInterface;
use SprykerFeature\Client\Storage\StorageClientInterface;

class WishlistStorage implements WishlistStorageInterface
{

    /**
     * @var StorageClientInterface
     */
    private $storageClient;

    /**
     * @var ProductClient
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
                ->getAbstractProductFromStorageByIdForCurrentLocale($item->getIdAbstractProduct());

            foreach ($productData['concrete_products'] as $product) {
                if ($product['sku'] !== $item->getSku()) {
                    continue;
                }
                $concreteProduct = new ConcreteProductTransfer();
                $concreteProduct->setName($product['name']);
                $concreteProduct->setSku($product['sku']);
                $concreteProduct->setAttributes($product['attributes']);
                $item->setConcreteProduct($concreteProduct);
            }
        }
    }

}

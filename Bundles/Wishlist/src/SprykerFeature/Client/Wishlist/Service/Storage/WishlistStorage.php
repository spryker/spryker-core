<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service\Storage;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;

class WishlistStorage implements WishlistStorageInterface
{
    /**
     * @var StorageClientInterface
     */
    private $storageClient;

    /**
     * @param StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param WishlistInterface $wishlist
     *
     * @return WishlistInterface
     */
    public function expandProductDetails(WishlistInterface $wishlist)
    {
        foreach ($wishlist->getItems() as $item) {
            $productData = $this->storageClient->get(
                sprintf('de.de_de.resource.abstract_product.%d', $item->getIdAbstractProduct())
            );
            $abstractProduct = new AbstractProductTransfer();
            $abstractProduct->setSku($productData['abstract_sku']);
            $abstractProduct->setAttributes($productData['abstract_attributes']);
            $abstractProduct->setIsActive($productData['available']);

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

            $item->setAbstractProduct($abstractProduct);
        }
    }
}

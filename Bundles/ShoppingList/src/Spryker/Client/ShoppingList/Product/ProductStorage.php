<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Product;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToPriceProductClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToProductClientInterface;

class ProductStorage implements ProductStorageInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToProductClientInterface
     */
    protected $productClient;

    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToProductClientInterface $productClient
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToPriceProductClientInterface $priceProductClient
     */
    public function __construct(
        ShoppingListToProductClientInterface $productClient,
        ShoppingListToPriceProductClientInterface $priceProductClient
    ) {
        $this->productClient = $productClient;
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function expandProductDetails(ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer): ShoppingListOverviewResponseTransfer
    {
        $productSkuCollection = $this->getProductSkuCollection($shoppingListResponseTransfer);
        if (empty($productSkuCollection)) {
            return $shoppingListResponseTransfer;
        }

        $validShoppingListItems = $this->getValidShoppingListItems($shoppingListResponseTransfer, $productSkuCollection);
        $shoppingListResponseTransfer->setItemsCollection($validShoppingListItems);

        return $shoppingListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer
     *
     * @return array
     */
    protected function getProductSkuCollection(ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer): array
    {
        $productSkuCollection = [];
        foreach ($shoppingListResponseTransfer->getItemsCollection()->getItems() as $item) {
            $productSkuCollection[] = $item->getSku();
        }

        return $productSkuCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer
     * @param array $productSkuCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function getValidShoppingListItems(ShoppingListOverviewResponseTransfer $shoppingListResponseTransfer, array $productSkuCollection): ShoppingListItemCollectionTransfer
    {
        $validShoppingListItems = new ShoppingListItemCollectionTransfer();

        $storageProductCollection = $this->getStorageProductCollection($productSkuCollection);
        foreach ($shoppingListResponseTransfer->getItemsCollection()->getItems() as $shoppingListItemTransfer) {
            if (!array_key_exists($shoppingListItemTransfer->getIdProduct(), $storageProductCollection)) {
                continue;
            }

            $shoppingListItemTransfer->setProduct($storageProductCollection[$shoppingListItemTransfer->getIdProduct()]);
            $validShoppingListItems->addItem($shoppingListItemTransfer);
        }

        return $validShoppingListItems;
    }

    /**
     * @param array $productSkuCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    protected function getStorageProductCollection(array $productSkuCollection): array
    {
        $result = [];
        $storageProductCollection = $this->productClient->getProductConcreteCollection($productSkuCollection);

        foreach ($storageProductCollection as $storageProductTransfer) {
            $currentPriceTransfer = $this->priceProductClient->resolveProductPrice($storageProductTransfer->getPrices());

            $storageProductTransfer->setPrice($currentPriceTransfer->getPrice());
            $storageProductTransfer->setPrices($currentPriceTransfer->getPrices());

            $result[$storageProductTransfer->getIdProductConcrete()] = $storageProductTransfer;
        }

        return $result;
    }
}

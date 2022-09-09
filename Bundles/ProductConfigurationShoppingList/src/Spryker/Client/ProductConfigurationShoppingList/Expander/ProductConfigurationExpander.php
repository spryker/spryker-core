<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface;

class ProductConfigurationExpander implements ProductConfigurationExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
     */
    protected ProductConfigurationShoppingListToProductConfigurationStorageClientInterface $productConfigurationStorageClient;

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface
     */
    protected ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductConfigurationShoppingListToProductConfigurationStorageClientInterface $productConfigurationStorageClient,
        ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expandShoppingListItemsWithProductConfiguration(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $expandedShoppingListItemTransfers = [];

        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $expandedShoppingListItemTransfers[] = $this->expandShoppingListItemWithProductConfiguration($shoppingListItemTransfer);
        }

        return $shoppingListTransfer->setItems(new ArrayObject($expandedShoppingListItemTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function expandShoppingListItemWithProductConfiguration(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
            ->findProductConfigurationInstanceBySku($shoppingListItemTransfer->getSkuOrFail());

        if (!$productConfigurationInstanceTransfer) {
            return $shoppingListItemTransfer;
        }

        $productConfigurationInstanceData = $this->utilEncodingService->encodeJson($productConfigurationInstanceTransfer->toArray());

        return $shoppingListItemTransfer
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer)
            ->setProductConfigurationInstanceData($productConfigurationInstanceData);
    }
}

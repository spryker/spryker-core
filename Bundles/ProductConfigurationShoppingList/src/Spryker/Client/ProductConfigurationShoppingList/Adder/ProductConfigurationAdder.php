<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Adder;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface;

class ProductConfigurationAdder implements ProductConfigurationAdderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
     */
    protected ProductConfigurationShoppingListToProductConfigurationStorageClientInterface $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(
        ProductConfigurationShoppingListToProductConfigurationStorageClientInterface $productConfigurationStorageClient
    ) {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addProductConfigurationToShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient
            ->findProductConfigurationInstanceBySku($shoppingListItemTransfer->getSkuOrFail());

        return $shoppingListItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }
}

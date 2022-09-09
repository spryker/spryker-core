<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface;

class ProductConfigurationExpander implements ProductConfigurationExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface
     */
    protected ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemsWithProductConfiguration(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $shoppingListItemTransfers = [];

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfers[] = $this->expandShoppingListItemWithProductConfiguration($shoppingListItemTransfer);
        }

        return $shoppingListItemCollectionTransfer->setItems(
            new ArrayObject($shoppingListItemTransfers),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function expandShoppingListItemWithProductConfiguration(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        $productConfigurationInstanceData = $shoppingListItemTransfer->getProductConfigurationInstanceData();

        if ($productConfigurationInstanceData === null) {
            return $shoppingListItemTransfer;
        }

        $productConfigurationInstanceData = $this->utilEncodingService->decodeJson($productConfigurationInstanceData, true) ?: [];

        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->fromArray($productConfigurationInstanceData, true);

        return $shoppingListItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }
}

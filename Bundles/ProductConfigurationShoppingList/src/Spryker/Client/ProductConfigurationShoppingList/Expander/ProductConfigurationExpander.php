<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;
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
        $productConfigurationInstanceCollectionTransfer = $this->getProductConfigurationInstanceCollection($shoppingListTransfer);

        if (!$productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()->count()) {
            return $shoppingListTransfer;
        }

        return $this->expandItemsWithProductConfigurationInstance(
            $shoppingListTransfer,
            $productConfigurationInstanceCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    protected function getProductConfigurationInstanceCollection(
        ShoppingListTransfer $shoppingListTransfer
    ): ProductConfigurationInstanceCollectionTransfer {
        $productConfigurationInstanceConditionsTransfer = new ProductConfigurationInstanceConditionsTransfer();

        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            if ($shoppingListItemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $productConfigurationInstanceConditionsTransfer->addSku($shoppingListItemTransfer->getSkuOrFail());
        }

        if (!$productConfigurationInstanceConditionsTransfer->getSkus()) {
            return new ProductConfigurationInstanceCollectionTransfer();
        }

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaTransfer())
            ->setProductConfigurationInstanceConditions($productConfigurationInstanceConditionsTransfer);

        return $this->productConfigurationStorageClient
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function expandItemsWithProductConfigurationInstance(
        ShoppingListTransfer $shoppingListTransfer,
        ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
    ): ShoppingListTransfer {
        $expandedShoppingListItemTransfers = new ArrayObject();
        $productConfigurationInstanceTransfersIndexedBySku = $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances();

        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $productConfigurationInstanceTransfer = $productConfigurationInstanceTransfersIndexedBySku[$shoppingListItemTransfer->getSkuOrFail()] ?? null;

            if (!$shoppingListItemTransfer->getProductConfigurationInstance() && $productConfigurationInstanceTransfer) {
                $productConfigurationInstanceData = $this->utilEncodingService
                    ->encodeJson($productConfigurationInstanceTransfer->toArray());

                $shoppingListItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer)
                    ->setProductConfigurationInstanceData($productConfigurationInstanceData);
            }

            $expandedShoppingListItemTransfers->append($shoppingListItemTransfer);
        }

        return $shoppingListTransfer->setItems($expandedShoppingListItemTransfers);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Business\Writer;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface;
use Spryker\Zed\ProductConfigurationShoppingList\Persistence\ProductConfigurationShoppingListEntityManagerInterface;

class ProductConfigurationWriter implements ProductConfigurationWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface
     */
    protected ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductConfigurationShoppingList\Persistence\ProductConfigurationShoppingListEntityManagerInterface
     */
    protected ProductConfigurationShoppingListEntityManagerInterface $productConfigurationShoppingListEntityManager;

    /**
     * @param \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductConfigurationShoppingList\Persistence\ProductConfigurationShoppingListEntityManagerInterface $productConfigurationShoppingListEntityManager
     */
    public function __construct(
        ProductConfigurationShoppingListToUtilEncodingServiceInterface $utilEncodingService,
        ProductConfigurationShoppingListEntityManagerInterface $productConfigurationShoppingListEntityManager
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->productConfigurationShoppingListEntityManager = $productConfigurationShoppingListEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function updateProductConfigurations(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemCollectionTransfer) {
            return $this->executeUpdateProductConfigurationsTransaction($shoppingListItemCollectionTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function executeUpdateProductConfigurationsTransaction(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer->setProductConfigurationInstanceData(
                $this->getProductConfigurationData($shoppingListItemTransfer),
            );

            $this->productConfigurationShoppingListEntityManager->updateProductConfigurationData($shoppingListItemTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return string|null
     */
    protected function getProductConfigurationData(ShoppingListItemTransfer $shoppingListItemTransfer): ?string
    {
        $productConfigurationInstanceTransfer = $shoppingListItemTransfer->getProductConfigurationInstance();

        if (!$productConfigurationInstanceTransfer) {
            return null;
        }

        return $this->utilEncodingService->encodeJson($productConfigurationInstanceTransfer->toArray());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdaterInterface;
use Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidatorInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface
     */
    protected ProductConfigurationShoppingListToProductConfigurationClientInterface $productConfigurationClient;

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidatorInterface
     */
    protected ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator;

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdaterInterface
     */
    protected ShoppingListItemProductConfigurationUpdaterInterface $shoppingListItemProductConfigurationUpdater;

    /**
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface $productConfigurationClient
     * @param \Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator
     * @param \Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdaterInterface $shoppingListItemProductConfigurationUpdater
     */
    public function __construct(
        ProductConfigurationShoppingListToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator,
        ShoppingListItemProductConfigurationUpdaterInterface $shoppingListItemProductConfigurationUpdater
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
        $this->productConfiguratorResponseValidator = $productConfiguratorResponseValidator;
        $this->shoppingListItemProductConfigurationUpdater = $shoppingListItemProductConfigurationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseTransfer = $this->productConfigurationClient->mapProductConfiguratorCheckSumResponse(
            $configuratorResponseData,
            $productConfiguratorResponseTransfer,
        );

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true)
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer);

        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfiguratorResponseValidator
            ->validateProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseProcessorResponseTransfer,
                $configuratorResponseData,
            );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $this->shoppingListItemProductConfigurationUpdater
            ->updateShoppingListItemProductConfiguration($productConfiguratorResponseProcessorResponseTransfer);
    }
}

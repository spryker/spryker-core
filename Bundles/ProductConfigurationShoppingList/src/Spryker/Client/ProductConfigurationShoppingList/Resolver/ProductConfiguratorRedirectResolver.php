<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Resolver;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_PRODUCT_CONFIGURATION_NOT_FOUND = 'product_configuration_shopping_list.error.configuration_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_UUID = '%uuid%';

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface
     */
    protected ProductConfigurationShoppingListToShoppingListClientInterface $shoppingListClient;

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface
     */
    protected ProductConfigurationShoppingListToProductConfigurationClientInterface $productConfigurationClient;

    /**
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface $shoppingListClient
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface $productConfigurationClient
     */
    public function __construct(
        ProductConfigurationShoppingListToShoppingListClientInterface $shoppingListClient,
        ProductConfigurationShoppingListToProductConfigurationClientInterface $productConfigurationClient
    ) {
        $this->shoppingListClient = $shoppingListClient;
        $this->productConfigurationClient = $productConfigurationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfiguratorRequestDataTransfer = $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail();

        $productConfigurationInstanceTransfer = $this->findShoppingListItemProductConfigurationInstance($productConfiguratorRequestDataTransfer);

        if (!$productConfigurationInstanceTransfer) {
            return $this->addErrorToProductConfiguratorRedirect(
                new ProductConfiguratorRedirectTransfer(),
                static::GLOSSARY_KEY_SHOPPING_LIST_PRODUCT_CONFIGURATION_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_UUID => $productConfiguratorRequestDataTransfer->getShoppingListItemUuid()],
            );
        }

        $productConfiguratorRequestTransfer = $this->mapProductConfigurationInstanceTransferToProductConfiguratorRequestTransfer(
            $productConfigurationInstanceTransfer,
            $productConfiguratorRequestTransfer,
        );

        return $this->productConfigurationClient->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findShoppingListItemProductConfigurationInstance(
        ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
    ): ?ProductConfigurationInstanceTransfer {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setUuid($productConfiguratorRequestDataTransfer->getShoppingListItemUuidOrFail());

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer);

        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer|null $shoppingListItemTransfer */
        $shoppingListItemTransfer = $this->shoppingListClient
            ->getShoppingListItemCollectionByUuid($shoppingListItemCollectionTransfer)
            ->getItems()
            ->getIterator()
            ->current();

        if (!$shoppingListItemTransfer || !$shoppingListItemTransfer->getProductConfigurationInstance()) {
            return null;
        }

        $shoppingListItemTransfer = $this->updateProductConfigurationQuantity($shoppingListItemTransfer);

        return $shoppingListItemTransfer->getProductConfigurationInstanceOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function updateProductConfigurationQuantity(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        $shoppingListItemTransfer
            ->getProductConfigurationInstanceOrFail()
            ->setQuantity($shoppingListItemTransfer->getQuantityOrFail());

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $configurationInstanceTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    protected function mapProductConfigurationInstanceTransferToProductConfiguratorRequestTransfer(
        ProductConfigurationInstanceTransfer $configurationInstanceTransfer,
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->fromArray(
            $configurationInstanceTransfer->toArray(),
            true,
        );

        return $productConfiguratorRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    protected function addErrorToProductConfiguratorRedirect(
        ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer,
        string $message,
        array $parameters = []
    ): ProductConfiguratorRedirectTransfer {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message)
            ->setParameters($parameters);

        return $productConfiguratorRedirectTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}

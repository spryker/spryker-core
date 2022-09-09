<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Updater;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface;

class ShoppingListItemProductConfigurationUpdater implements ShoppingListItemProductConfigurationUpdaterInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_ITEM_NOT_FOUND = 'product_configuration_shopping_list.error.item_not_found';

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface
     */
    protected ProductConfigurationShoppingListToShoppingListClientInterface $shoppingListClient;

    /**
     * @var \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface
     */
    protected ProductConfigurationShoppingListToCustomerClientInterface $customerClient;

    /**
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface $shoppingListClient
     * @param \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface $customerClient
     */
    public function __construct(
        ProductConfigurationShoppingListToShoppingListClientInterface $shoppingListClient,
        ProductConfigurationShoppingListToCustomerClientInterface $customerClient
    ) {
        $this->shoppingListClient = $shoppingListClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function updateShoppingListItemProductConfiguration(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $shoppingListItemTransfer = $this->findShoppingListItem($productConfiguratorResponseProcessorResponseTransfer);

        if (!$shoppingListItemTransfer) {
            return $this->addErrorMessages(
                $productConfiguratorResponseProcessorResponseTransfer,
                [static::GLOSSARY_KEY_SHOPPING_LIST_ITEM_NOT_FOUND],
            );
        }

        $shoppingListItemToBeUpdated = $this->prepareShoppingListItemForUpdate(
            $shoppingListItemTransfer,
            $productConfiguratorResponseProcessorResponseTransfer,
        );

        $shoppingListItemResponseTransfer = $this->shoppingListClient->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);

        if ($shoppingListItemResponseTransfer->getIsSuccess()) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->setIsSuccessful(true)
                ->setIdShoppingList($shoppingListItemResponseTransfer->getShoppingListItemOrFail()->getFkShoppingListOrFail());
        }

        return $this->addErrorMessages(
            $productConfiguratorResponseProcessorResponseTransfer,
            $shoppingListItemResponseTransfer->getErrors(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array<string> $errors
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function addErrorMessages(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $errors
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        foreach ($errors as $error) {
            $productConfiguratorResponseProcessorResponseTransfer->addMessage(
                (new MessageTransfer())->setValue($error),
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function prepareShoppingListItemForUpdate(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ShoppingListItemTransfer {
        $productConfigurationInstanceTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponseOrFail()
            ->getProductConfigurationInstanceOrFail();

        $shoppingListItemTransfer = $this->setIdCompanyUser($shoppingListItemTransfer);
        $shoppingListItemTransfer = $this->setItemQuantity($shoppingListItemTransfer, $productConfigurationInstanceTransfer);

        return $shoppingListItemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function setIdCompanyUser(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer && $customerTransfer->getCompanyUserTransfer()) {
            $shoppingListItemTransfer->setIdCompanyUser($customerTransfer->getCompanyUserTransferOrFail()->getIdCompanyUser());
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function setItemQuantity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ShoppingListItemTransfer {
        $availableQuantity = (int)$productConfigurationInstanceTransfer->getAvailableQuantity();

        if ($availableQuantity && $availableQuantity < $shoppingListItemTransfer->getQuantity()) {
            $shoppingListItemTransfer->setQuantity($availableQuantity);
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer|null
     */
    protected function findShoppingListItem(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ?ShoppingListItemTransfer {
        $shoppingListItemUuid = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponseOrFail()
            ->getShoppingListItemUuidOrFail();

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem((new ShoppingListItemTransfer())->setUuid($shoppingListItemUuid));

        return $this->shoppingListClient
            ->getShoppingListItemCollectionByUuid($shoppingListItemCollectionTransfer)
            ->getItems()
            ->getIterator()
            ->current();
    }
}

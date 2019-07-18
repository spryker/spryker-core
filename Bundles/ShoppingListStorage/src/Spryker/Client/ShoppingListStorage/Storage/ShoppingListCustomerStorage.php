<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\Storage;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageClientInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;

class ShoppingListCustomerStorage implements ShoppingListCustomerStorageInterface
{
    /**
     * @var \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ShoppingListStorageToStorageClientInterface $storageClient,
        ShoppingListStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer|null
     */
    public function getShoppingListCustomerStorageByCustomerReference(string $customerReference): ?ShoppingListCustomerStorageTransfer
    {
        $key = $this->generateKey($customerReference);
        $shoppingListStorageData = $this->storageClient->get($key);

        if (!$shoppingListStorageData) {
            return null;
        }

        return $this->mapToShoppingListStorage($shoppingListStorageData);
    }

    /**
     * @param string $customerReference
     *
     * @return string
     */
    protected function generateKey(string $customerReference): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($customerReference);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ShoppingListStorageConfig::RESOURCE_TYPE_SHOPPING_LIST)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array $shoppingListStorageData
     *
     * @return \Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer
     */
    protected function mapToShoppingListStorage(array $shoppingListStorageData): ShoppingListCustomerStorageTransfer
    {
        return (new ShoppingListCustomerStorageTransfer())
            ->fromArray($shoppingListStorageData, true);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\ShoppingListCustomerPublisher;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManagerInterface;
use Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface;

class ShoppingListCustomerStoragePublisher implements ShoppingListCustomerStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManagerInterface
     */
    protected $shoppingListStorageEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface
     */
    protected $shoppingListStorageRepository;

    /**
     * ShoppingListCustomerStorageWriter constructor.
     *
     * @param \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManagerInterface $shoppingListStorageEntityManager
     * @param \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface $shoppingListStorageRepository
     */
    public function __construct(
        ShoppingListStorageEntityManagerInterface $shoppingListStorageEntityManager,
        ShoppingListStorageRepositoryInterface $shoppingListStorageRepository
    ) {
        $this->shoppingListStorageEntityManager = $shoppingListStorageEntityManager;
        $this->shoppingListStorageRepository = $shoppingListStorageRepository;
    }

    /**
     * @param string[] $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void
    {
        $shoppingListCustomerStorageCollection = $this->shoppingListStorageRepository
            ->findShoppingListCustomerStorageEntitiesByCustomerReferences($customerReferences);
        $this->appendToShoppingListCustomerStorageCollectionIfNotExists($shoppingListCustomerStorageCollection, $customerReferences);

        foreach ($shoppingListCustomerStorageCollection as $shoppingListCustomerStorage) {
            $shoppingListCustomerStorageTransfer = new ShoppingListCustomerStorageTransfer();
            $shoppingListCustomerStorageTransfer->setUpdatedAt(time());
            $shoppingListCustomerStorage->setData($shoppingListCustomerStorageTransfer->toArray());

            $this->storeData($shoppingListCustomerStorage);
        }
    }

    /**
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage $shoppingListCustomerStorage
     *
     * @return void
     */
    protected function storeData(SpyShoppingListCustomerStorage $shoppingListCustomerStorage): void
    {
        $this->shoppingListStorageEntityManager->saveShoppingListCustomerStorage($shoppingListCustomerStorage);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $shoppingListCustomerStorageCollection
     * @param array $customerReferences
     *
     * @return void
     */
    protected function appendToShoppingListCustomerStorageCollectionIfNotExists(ObjectCollection $shoppingListCustomerStorageCollection, array $customerReferences): void
    {
        $existingCustomerReferences = $shoppingListCustomerStorageCollection->toKeyValue(
            'idShoppingListCustomerStorage',
            'customerReference'
        );

        foreach (array_diff($customerReferences, $existingCustomerReferences) as $customerReference) {
            $shoppingListCustomerStorageEntity = (new SpyShoppingListCustomerStorage())
                ->setCustomerReference($customerReference);
            $this->storeData($shoppingListCustomerStorageEntity);
            $shoppingListCustomerStorageCollection->append($shoppingListCustomerStorageEntity);
        }
    }
}

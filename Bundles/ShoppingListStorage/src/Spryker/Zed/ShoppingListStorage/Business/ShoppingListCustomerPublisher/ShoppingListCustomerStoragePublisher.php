<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\ShoppingListCustomerPublisher;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingList;
use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
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
        $shoppingListEntities = $this->shoppingListStorageRepository
            ->findShoppingListEntitiesByCustomerReferences($customerReferences)
            ->toKeyIndex('customerReference');
        $shoppingListCustomerStorageEntities = $this->shoppingListStorageRepository
            ->findShoppingListCustomerStorageEntitiesByCustomerReferences($customerReferences)
            ->toKeyIndex('customerReference');

        $this->storeData($shoppingListEntities, $shoppingListCustomerStorageEntities);
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList[] $shoppingListEntities
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[] $shoppingListCustomerStorageEntities
     *
     * @return void
     */
    protected function storeData(array $shoppingListEntities, array $shoppingListCustomerStorageEntities): void
    {
        foreach ($shoppingListEntities as $customerReference => $shoppingListEntity) {
            if (isset($shoppingListCustomerStorageEntities[$customerReference])) {
                $this->storeDataSet($shoppingListEntity, $shoppingListCustomerStorageEntities[$customerReference]);
                continue;
            }

            $this->storeDataSet($shoppingListEntity);
        }
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList $shoppingListEntity
     * @param null|\Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyShoppingList $shoppingListEntity, ?SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity = null)
    {
        if (!isset($shoppingListCustomerStorageEntity)) {
            $shoppingListCustomerStorageEntity = new SpyShoppingListCustomerStorage();
        }
        $shoppingListCustomerStorageEntity->setCustomerReference($shoppingListEntity->getCustomerReference());
        $shoppingListCustomerStorageTransfer = new ShoppingListCustomerStorageTransfer();
        $shoppingListCustomerStorageTransfer->setUpdatedAt(time());
        $shoppingListCustomerStorageEntity->setData($shoppingListCustomerStorageTransfer->toArray());

        $this->shoppingListStorageEntityManager->saveShoppingListCustomerStorage($shoppingListCustomerStorageEntity);
    }
}

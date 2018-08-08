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
        $spyShoppingListEntities = $this->shoppingListStorageRepository
            ->findShoppingListEntitiesByCustomerReferences($customerReferences);
        $spyShoppingListCustomerStorageEntities = $this->shoppingListStorageRepository
            ->findShoppingListCustomerStorageEntitiesByCustomerReferences($customerReferences);

        $this->storeData($spyShoppingListEntities, $spyShoppingListCustomerStorageEntities);
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList[] $spyShoppingListEntities
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[] $shoppingListCustomerStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyShoppingListEntities, array $shoppingListCustomerStorageEntities): void
    {
        foreach ($spyShoppingListEntities as $customerReference => $spyShoppingListEntity) {
            if (isset($shoppingListCustomerStorageEntities[$customerReference])) {
                $this->storeDataSet($spyShoppingListEntity, $shoppingListCustomerStorageEntities[$customerReference]);
                continue;
            }

            $this->storeDataSet($spyShoppingListEntity);
        }
    }

    /**
     * @param \Orm\Zed\ShoppingList\Persistence\SpyShoppingList $spyShoppingListEntity
     * @param null|\Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyShoppingList $spyShoppingListEntity, ?SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity = null)
    {
        if (!isset($shoppingListCustomerStorageEntity)) {
            $shoppingListCustomerStorageEntity = new SpyShoppingListCustomerStorage();
        }
        $shoppingListCustomerStorageEntity->setCustomerReference($spyShoppingListEntity->getCustomerReference());
        $shoppingListCustomerStorageTransfer = new ShoppingListCustomerStorageTransfer();
        $shoppingListCustomerStorageTransfer->setUpdatedAt(time());
        $shoppingListCustomerStorageEntity->setData($shoppingListCustomerStorageTransfer->toArray());

        $this->shoppingListStorageEntityManager->saveShoppingListCustomerStorage($shoppingListCustomerStorageEntity);
    }
}

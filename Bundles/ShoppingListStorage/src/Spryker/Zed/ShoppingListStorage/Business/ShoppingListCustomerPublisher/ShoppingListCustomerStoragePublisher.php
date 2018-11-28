<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\ShoppingListCustomerPublisher;

use Generated\Shared\Transfer\ShoppingListCustomerStorageTransfer;
use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
use Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManagerInterface;
use Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface;

class ShoppingListCustomerStoragePublisher implements ShoppingListCustomerStoragePublisherInterface
{
    /**
     * The name on the customer_reference column related to Propel's ObjectCollection.
     * There is no equivalent replacement in *TableMap constants.
     */
    public const COL_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageEntityManagerInterface
     */
    protected $shoppingListStorageEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface
     */
    protected $shoppingListStorageRepository;

    /**
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
        $shoppingListCustomerStorageEntities = $this->shoppingListStorageRepository
            ->findShoppingListCustomerStorageEntitiesByCustomerReferences($customerReferences)
            ->toKeyIndex(static::COL_CUSTOMER_REFERENCE);

        $this->storeData($customerReferences, $shoppingListCustomerStorageEntities);
    }

    /**
     * @param string[] $customerReferences
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage[] $shoppingListCustomerStorageEntities
     *
     * @return void
     */
    protected function storeData(array $customerReferences, array $shoppingListCustomerStorageEntities): void
    {
        foreach ($customerReferences as $customerReference) {
            if (isset($shoppingListCustomerStorageEntities[$customerReference])) {
                $this->storeDataSet($customerReference, $shoppingListCustomerStorageEntities[$customerReference]);
                continue;
            }

            $this->storeDataSet($customerReference);
        }
    }

    /**
     * @param string $customerReference
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage|null $shoppingListCustomerStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(string $customerReference, ?SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity = null)
    {
        if (!isset($shoppingListCustomerStorageEntity)) {
            $shoppingListCustomerStorageEntity = new SpyShoppingListCustomerStorage();
        }
        $shoppingListCustomerStorageEntity->setCustomerReference($customerReference);
        $shoppingListCustomerStorageTransfer = new ShoppingListCustomerStorageTransfer();
        $shoppingListCustomerStorageTransfer->setUpdatedAt(time());
        $shoppingListCustomerStorageEntity->setData($shoppingListCustomerStorageTransfer->toArray());

        $this->shoppingListStorageEntityManager->saveShoppingListCustomerStorage($shoppingListCustomerStorageEntity);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @deprecated use \Spryker\Zed\ShoppingListStorage\Communication\Plugin\Synchronization\ShoppingListSynchronizationDataBulkPlugin instead.
 *
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShoppingListStorage\Business\ShoppingListStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListStorage\Communication\ShoppingListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShoppingListStorage\ShoppingListStorageConfig getConfig()
 */
class ShoppingListSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ShoppingListStorageConfig::SHOPPING_LIST_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $shoppingListCustomerStorageEntities = $this->getRepository()->findShoppingListCustomerStorageEntitiesByIds($ids);

        foreach ($shoppingListCustomerStorageEntities as $shoppingListCustomerStorageEntity) {
            $synchronizationDataTransfers[] = $this->createSynchronizationDataTransfer($shoppingListCustomerStorageEntity);
        }

        return $synchronizationDataTransfers;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ShoppingListStorageConfig::SHOPPING_LIST_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getShoppingListSynchronizationPoolName();
    }

    /**
     * @param \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(
        SpyShoppingListCustomerStorage $shoppingListCustomerStorageEntity
    ): SynchronizationDataTransfer {
        /** @var string $shoppingListCustomerStorageData */
        $shoppingListCustomerStorageData = $shoppingListCustomerStorageEntity->getData();

        return (new SynchronizationDataTransfer())
            ->setData($shoppingListCustomerStorageData)
            ->setKey($shoppingListCustomerStorageEntity->getKey());
    }
}

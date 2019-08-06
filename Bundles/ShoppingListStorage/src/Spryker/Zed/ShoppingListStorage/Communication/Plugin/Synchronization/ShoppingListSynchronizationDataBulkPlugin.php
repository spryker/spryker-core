<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyShoppingListCustomerStorageEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ShoppingListStorage\Persistence\Map\SpyShoppingListCustomerStorageTableMap;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShoppingListStorage\Business\ShoppingListStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListStorage\Communication\ShoppingListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShoppingListStorage\ShoppingListStorageConfig getConfig()
 */
class ShoppingListSynchronizationDataBulkPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $filterTransfer = $this->createFilterTransfer($offset, $limit);
        $shoppingListCustomerStorageEntityTransfers = $this->getRepository()->findFilteredShoppingListCustomerStorageEntities($filterTransfer, $ids);

        foreach ($shoppingListCustomerStorageEntityTransfers as $shoppingListCustomerStorageEntityTransfer) {
            $synchronizationDataTransfers[] = $this->createSynchronizationDataTransfer($shoppingListCustomerStorageEntityTransfer);
        }

        return $synchronizationDataTransfers;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(SpyShoppingListCustomerStorageTableMap::COL_ID_SHOPPING_LIST_CUSTOMER_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyShoppingListCustomerStorageEntityTransfer $shoppingListCustomerStorageEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(
        SpyShoppingListCustomerStorageEntityTransfer $shoppingListCustomerStorageEntityTransfer
    ): SynchronizationDataTransfer {
        /** @var string $shoppingListCustomerStorageData */
        $shoppingListCustomerStorageData = $shoppingListCustomerStorageEntityTransfer->getData();

        return (new SynchronizationDataTransfer())
            ->setData($shoppingListCustomerStorageData)
            ->setKey($shoppingListCustomerStorageEntityTransfer->getKey());
    }
}

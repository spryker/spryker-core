<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\StoreStorageConditionsTransfer;
use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;
use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreStorage\Communication\StoreStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageRepositoryInterface getRepository()
 */
class StoreSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return StoreStorageConfig::STORE_RESOURCE_NAME;
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
     * - Returns SynchronizationData transfers for StoreStorage entities based on offset, limit and ids.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        return $this->getFacade()->getStoreStorageSynchronizationDataTransfers(
            $this->createStoreStorageCriteriaTransfer($offset, $limit, $ids),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<mixed>
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
        return StoreStorageConfig::STORE_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()
            ->getConfig()
            ->getStoreSynchronizationPoolName();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array<int> $ids
     *
     * @return \Generated\Shared\Transfer\StoreStorageCriteriaTransfer
     */
    protected function createStoreStorageCriteriaTransfer(int $offset, int $limit, array $ids): StoreStorageCriteriaTransfer
    {
        $storeStorageCriteriaTransfer = new StoreStorageCriteriaTransfer();

        if ($ids) {
            $storeStorageCriteriaTransfer
                ->setStoreStorageConditions(
                    (new StoreStorageConditionsTransfer())
                        ->setStoreIds($ids),
                );
        }

        return $storeStorageCriteriaTransfer
            ->setPagination((new PaginationTransfer())
                ->setOffset($offset)
                ->setLimit($limit));
    }
}

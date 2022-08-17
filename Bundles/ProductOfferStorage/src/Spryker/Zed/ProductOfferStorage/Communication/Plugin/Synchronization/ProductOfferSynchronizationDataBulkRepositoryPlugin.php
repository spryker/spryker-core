<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorage;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferStorage\Communication\ProductOfferStorageCommunicationFactory getFactory()
 */
class ProductOfferSynchronizationDataBulkRepositoryPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return ProductOfferStorageConfig::RESOURCE_PRODUCT_OFFER_NAME;
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
        return true;
    }

    /**
     * {@inheritDoc}
     * - Returns SynchronizationDataTransfer[] for ProductOfferStorage entity.
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
        $synchronizationDataTransfers = [];
        $productOfferStorageEntities = $this->getProductOfferStorageEntities($offset, $limit, $ids);

        foreach ($productOfferStorageEntities as $productOfferStorageEntity) {
            $synchronizationDataTransfers[] = $this->createSynchronizationDataTransfer($productOfferStorageEntity);
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
        return ProductOfferStorageConfig::PRODUCT_OFFER_SYNC_STORAGE_QUEUE;
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
        return $this->getConfig()
            ->getProductOfferSynchronizationPoolName();
    }

    /**
     * @param \Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorage $productOfferStorage
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(SpyProductOfferStorage $productOfferStorage): SynchronizationDataTransfer
    {
        return (new SynchronizationDataTransfer())
            ->fromArray($productOfferStorage->toArray(), true);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductOfferStorageEntities(int $offset, int $limit, array $ids): ObjectCollection
    {
        $productOfferStorageQuery = SpyProductOfferStorageQuery::create();

        if ($ids) {
            $productOfferStorageQuery->filterByIdProductOfferStorage_In($ids);
        }
        $productOfferStorageQuery->offset($offset);
        $productOfferStorageQuery->limit($limit);

        return $productOfferStorageQuery->find();
    }
}

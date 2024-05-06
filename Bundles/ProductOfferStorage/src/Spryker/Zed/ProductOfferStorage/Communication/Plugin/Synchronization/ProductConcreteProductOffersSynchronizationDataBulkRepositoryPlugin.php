<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Propel\Runtime\Collection\Collection;
use Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStorage\Business\ProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferStorage\Communication\ProductOfferStorageCommunicationFactory getFactory()
 */
class ProductConcreteProductOffersSynchronizationDataBulkRepositoryPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return ProductOfferStorageConfig::RESOURCE_PRODUCT_CONCRETE_PRODUCT_OFFERS_NAME;
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
     *  - Returns SynchronizationDataTransfer[] for ProductConcreteProductOffersStorage entity.
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
        $productConcreteProductOffersStorageEntities = $this->getProductConcreteProductOffersStorageEntities($offset, $limit, $ids);

        foreach ($productConcreteProductOffersStorageEntities as $productConcreteProductOffersStorageEntity) {
            $synchronizationDataTransfers[] = $this->createSynchronizationDataTransfer($productConcreteProductOffersStorageEntity);
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
     * @param \Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(
        SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
    ): SynchronizationDataTransfer {
        return (new SynchronizationDataTransfer())
            ->fromArray($productConcreteProductOffersStorageEntity->toArray(), true);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getProductConcreteProductOffersStorageEntities(int $offset, int $limit, array $ids): Collection
    {
        $productConcreteProductOffersStorageQuery = SpyProductConcreteProductOffersStorageQuery::create();

        if ($ids) {
            $productConcreteProductOffersStorageQuery->filterByIdProductConcreteProductOffersStorage_In($ids);
        }
        $productConcreteProductOffersStorageQuery->offset($offset);
        $productConcreteProductOffersStorageQuery->limit($limit);

        return $productConcreteProductOffersStorageQuery->find();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorage;
use Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorageQuery;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOfferStorage\Communication\PriceProductOfferStorageCommunicationFactory getFactory()
 */
class PriceProductOfferSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return PriceProductOfferStorageConfig::RESOURCE_PRICE_PRODUCT_OFFER_OFFER_NAME;
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
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $productConcreteProductOfferPriceStorageQuery = SpyProductConcreteProductOfferPriceStorageQuery::create();
        $productConcreteProductOfferPriceStorageQuery->offset($offset)
            ->limit($limit);
        if ($ids) {
            $productConcreteProductOfferPriceStorageQuery->filterByIdProductConcreteProductOfferPriceStorage_In($ids);
        }
        $productConcreteProductOfferPriceStorageEntities = $productConcreteProductOfferPriceStorageQuery->find();
        foreach ($productConcreteProductOfferPriceStorageEntities as $productConcreteProductOfferPriceStorageEntity) {
            $synchronizationDataTransfers[] = $this->createSynchronizationDataTransfer($productConcreteProductOfferPriceStorageEntity);
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
        return PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_QUEUE;
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
            ->getPriceProductOfferSynchronizationPoolName();
    }

    /**
     * @param \Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorage $productOfferEntity
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(SpyProductConcreteProductOfferPriceStorage $productOfferEntity): SynchronizationDataTransfer
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        /** @var string $data */
        $data = $productOfferEntity->getData();

        $synchronizationDataTransfer->setData($data);
        $synchronizationDataTransfer->setKey($productOfferEntity->getKey());

        return $synchronizationDataTransfer;
    }
}

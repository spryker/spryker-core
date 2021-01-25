<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 */
class ProductConcreteProductOffersSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return MerchantProductOfferStorageConfig::RESOURCE_PRODUCT_CONCRETE_PRODUCT_OFFERS_NAME;
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
     *  - Returns SynchronizationDataTransfer[] for ProductConcreteProductOffersStorage entity.
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
        return MerchantProductOfferStorageConfig::MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_QUEUE;
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
            ->getMerchantProductOfferSynchronizationPoolName();
    }

    /**
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(
        SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
    ): SynchronizationDataTransfer {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        /** @var string $data */
        $data = $productConcreteProductOffersStorageEntity->getData();
        $synchronizationDataTransfer->setData($data);
        $synchronizationDataTransfer->setKey($productConcreteProductOffersStorageEntity->getKey());

        return $synchronizationDataTransfer;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductConcreteProductOffersStorageEntities(int $offset, int $limit, array $ids): ObjectCollection
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

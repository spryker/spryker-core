<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
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
        return MerchantProductOfferStorageConfig::RESOURCE_MERCHANT_PRODUCT_OFFER_NAME;
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
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage $productOfferStorage
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(SpyProductOfferStorage $productOfferStorage): SynchronizationDataTransfer
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        /** @var string $data */
        $data = $productOfferStorage->getData();
        $synchronizationDataTransfer->setData($data);
        $synchronizationDataTransfer->setKey($productOfferStorage->getKey());

        return $synchronizationDataTransfer;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage[]|\Propel\Runtime\Collection\ObjectCollection
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

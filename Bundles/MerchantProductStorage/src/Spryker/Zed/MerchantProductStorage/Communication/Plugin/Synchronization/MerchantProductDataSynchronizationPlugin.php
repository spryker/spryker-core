<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\MerchantProductFilterCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorage;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\MerchantProductStorage\MerchantProductStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Communication\MerchantProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductStorage\Business\MerchantProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepositoryInterface getRepository()
 */
class MerchantProductDataSynchronizationPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return array
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $productOfferStorageEntities = $this->getMerchantProductStorageEntities($offset, $limit, $ids);

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
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantProductStorageConfig::RESOURCE_MERCHANT_PRODUCT_ABSTRACT_NAME;
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
        return MerchantProductStorageConfig::MERCHANT_PRODUCT_ABSTRACT_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getMerchantSynchronizationPoolName();
    }

    /**
     * @param \Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorage $merchantProductAbstractStorage
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(SpyMerchantProductAbstractStorage $merchantProductAbstractStorage): SynchronizationDataTransfer
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        /** @var string $data */
        $data = $merchantProductAbstractStorage->getData();
        $synchronizationDataTransfer->setData($data);
        $synchronizationDataTransfer->setKey($merchantProductAbstractStorage->getKey());

        return $synchronizationDataTransfer;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return \Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getMerchantProductStorageEntities(int $offset, int $limit, array $ids): ObjectCollection
    {
        $merchantProductFilterCriteriaTransfer = new MerchantProductFilterCriteriaTransfer();
        $merchantProductFilterCriteriaTransfer->setOffset($offset)
        ->setLimit($limit)
        ->setIdProductAbstractMerchantStorages($ids);

        return $this->getRepository()->getMerchantProductStorageEntitiesByFilterCriteria($merchantProductFilterCriteriaTransfer);
    }
}

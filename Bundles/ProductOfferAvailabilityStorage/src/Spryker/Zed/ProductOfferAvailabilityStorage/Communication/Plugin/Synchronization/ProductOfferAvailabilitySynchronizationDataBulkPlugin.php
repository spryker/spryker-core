<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyProductOfferAvailabilityStorageEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\Map\SpyProductOfferAvailabilityStorageTableMap;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Communication\ProductOfferAvailabilityStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageFacadeInterface getFacade()
 */
class ProductOfferAvailabilitySynchronizationDataBulkPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME;
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

        $productOfferAvailabilityStorageEntityTransfers = $this->getRepository()
            ->getFilteredProductOfferAvailabilityStorageEntityTransfers($filterTransfer, $ids);

        foreach ($productOfferAvailabilityStorageEntityTransfers as $productOfferAvailabilityStorageEntityTransfer) {
            $synchronizationDataTransfers[] = $this->createSynchronizationDataTransfer($productOfferAvailabilityStorageEntityTransfer);
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
        return ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getProductOfferAvailabilitySynchronizationPoolName();
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
            ->setOrderBy(SpyProductOfferAvailabilityStorageTableMap::COL_ID_PRODUCT_OFFER_AVAILABILITY_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductOfferAvailabilityStorageEntityTransfer $productOfferAvailabilityStorageEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    protected function createSynchronizationDataTransfer(
        SpyProductOfferAvailabilityStorageEntityTransfer $productOfferAvailabilityStorageEntityTransfer
    ): SynchronizationDataTransfer {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setData($productOfferAvailabilityStorageEntityTransfer->getData());
        $synchronizationDataTransfer->setKey($productOfferAvailabilityStorageEntityTransfer->getKey());
        $synchronizationDataTransfer->setStore($productOfferAvailabilityStorageEntityTransfer->getStore());

        return $synchronizationDataTransfer;
    }
}

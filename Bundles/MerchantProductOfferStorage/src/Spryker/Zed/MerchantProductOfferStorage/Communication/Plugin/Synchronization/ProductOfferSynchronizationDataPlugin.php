<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaFilterTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\Map\SpyProductOfferStorageTableMap;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Communication\MerchantProductOfferStorageCommunicationFactory getFactory()
 */
class ProductOfferSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return false;
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

        $productOfferStorageCriteriaFilterTransfer = new ProductOfferStorageCriteriaFilterTransfer();
        $productOfferStorageCriteriaFilterTransfer->setProductOfferStorageIds($ids);
        $productOfferStorageCriteriaFilterTransfer->setFilter($this->createFilterTransfer($offset, $limit));

        $productOfferTransfers = $this->getRepository()->findProductOfferStorage($productOfferStorageCriteriaFilterTransfer);

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();

            /** @var string $data */
            $data = $productOfferTransfer->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($productOfferTransfer->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
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
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(SpyProductOfferStorageTableMap::COL_ID_PRODUCT_OFFER_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}

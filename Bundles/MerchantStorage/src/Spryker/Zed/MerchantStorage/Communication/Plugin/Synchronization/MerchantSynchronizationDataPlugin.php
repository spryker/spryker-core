<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStorage\Communication\MerchantStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface getRepository()()
 */
class MerchantSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * @uses \Propel\Runtime\ActiveQuery\Criteria::ASC
     */
    protected const ORDER_DIRECTION = 'ASC';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantStorageConfig::MERCHANT_RESOURCE_NAME;
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
        $merchantStorageEntities = $this->getRepository()
            ->getFilteredMerchantStorageEntityTransfers(
                (new MerchantCriteriaTransfer())
                    ->setFilter($this->createFilterTransfer($offset, $limit))
                    ->setMerchantIds($ids)
            );

        return $this->mapMerchantStorageEntitiesToSynchronizationDataTransfers($merchantStorageEntities);
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
        return MerchantStorageConfig::MERCHANT_SYNC_STORAGE_QUEUE;
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
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $merchantStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    protected function mapMerchantStorageEntitiesToSynchronizationDataTransfers(ObjectCollection $merchantStorageEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($merchantStorageEntities as $merchantStorageEntity) {
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->fromArray($merchantStorageEntity->toArray(), true);
        }

        return $synchronizationDataTransfers ?? [];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\CmsSlotStorage\Persistence\Map\SpyCmsSlotStorageTableMap;
use Spryker\Shared\CmsSlotStorage\CmsSlotStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface getRepository()()
 * @method \Spryker\Zed\CmsSlotStorage\CmsSlotStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotStorage\Communication\CmsSlotStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotStorage\Business\CmsSlotStorageFacadeInterface getFacade()
 */
class CmsSlotSynchronizationDataBulkPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return CmsSlotStorageConstants::CMS_SLOT_RESOURCE_NAME;
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
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        $cmsSlotStorageEntities = $this->getRepository()->getFilteredCmsSlotStorageEntities($filterTransfer, $ids);

        foreach ($cmsSlotStorageEntities as $cmsSlotStorageEntity) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($cmsSlotStorageEntity->getData());
            $synchronizationDataTransfer->setKey($cmsSlotStorageEntity->getKey());

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
        return CmsSlotStorageConstants::CMS_SLOT_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getCmsSlotSynchronizationPoolName();
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
            ->setOrderBy(SpyCmsSlotStorageTableMap::COL_ID_CMS_SLOT_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}

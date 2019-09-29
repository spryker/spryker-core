<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentStorage\Communication\ContentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
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
        return ContentStorageConfig::CONTENT_RESOURCE_NAME;
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
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        foreach ($this->findContentStorage($ids) as $contentStorageTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($contentStorageTransfer->getData());
            $synchronizationDataTransfer->setKey($contentStorageTransfer->getKey());

            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ContentStorageTransfer[]
     */
    protected function findContentStorage(array $ids = []): array
    {
        if ($ids === []) {
            return $this->getRepository()->findAllContentStorage();
        }

        return $this->getRepository()->findContentStorageByContentIds($ids);
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
        return ContentStorageConfig::CONTENT_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->getSynchronizationPoolName();
    }
}

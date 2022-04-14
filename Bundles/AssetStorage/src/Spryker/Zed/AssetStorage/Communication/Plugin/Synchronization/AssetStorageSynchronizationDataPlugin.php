<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Shared\AssetStorage\AssetStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\Communication\AssetStorageCommunicationFactory getFactory()
 */
class AssetStorageSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
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
        return AssetStorageConfig::ASSET_SLOT_RESOURCE_NAME;
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
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        foreach ($this->findAssetStorage($ids) as $assetStorageEntityTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($assetStorageEntityTransfer->getData());
            $synchronizationDataTransfer->setKey($assetStorageEntityTransfer->getAssetSlot());
            $synchronizationDataTransfer->setStore($assetStorageEntityTransfer->getStore());

            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    protected function findAssetStorage(array $ids): array
    {
        if ($ids === []) {
            return $this->getRepository()->findAssetStorages();
        }

        return $this->getRepository()->findAssetStoragesByAssetIds($ids);
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
        return AssetStorageConfig::ASSET_SYNC_STORAGE_QUEUE;
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
        return $this->getFactory()->getConfig()->findSynchronizationPoolName();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class RepositoryExporter extends Exporter
{
    /**
     * @param array $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface $plugin
     *
     * @return void
     */
    protected function exportData(array $ids, SynchronizationDataRepositoryPluginInterface $plugin)
    {
        $synchronizationEntities = $plugin->getData($ids);
        $count = count($synchronizationEntities);
        $loops = $count / $this->chunkSize;
        $offset = 0;

        for ($i = 0; $i < $loops; $i++) {
            $chunkOfSynchronizationEntitiesTransfers = array_slice($synchronizationEntities, $offset, $this->chunkSize);
            $this->syncData($plugin, $chunkOfSynchronizationEntitiesTransfers);
            $offset += $this->chunkSize;
        }
    }

    /**
     * @param bool $hasStore
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $entity
     *
     * @return null|string
     */
    protected function getStore(bool $hasStore, SynchronizationDataTransfer $entity)
    {
        if ($hasStore) {
            return $entity->getStore();
        }

        return null;
    }
}

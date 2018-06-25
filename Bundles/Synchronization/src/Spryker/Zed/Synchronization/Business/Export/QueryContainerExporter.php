<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

class QueryContainerExporter extends Exporter
{
    /**
     * @param array $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     *
     * @return void
     */
    protected function exportData(array $ids, SynchronizationDataQueryContainerPluginInterface $plugin)
    {
        $query = $plugin->queryData($ids);
        $count = $query->count();
        $loops = $count / $this->chunkSize;
        $offset = 0;

        for ($i = 0; $i < $loops; $i++) {
            $synchronizationEntities = $plugin->queryData($ids)
                ->offset($offset)
                ->limit($this->chunkSize)
                ->find()
                ->getData();

            $this->syncData($plugin, $synchronizationEntities);
            $offset += $this->chunkSize;
        }
    }

    /**
     * @param bool $hasStore
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return string|null
     */
    protected function getStore(bool $hasStore, ActiveRecordInterface $entity)
    {
        if ($hasStore) {
            return $entity->getStore();
        }

        return null;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class Exporter implements ExporterInterface
{
    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface
     */
    protected $queueMessageCreator;

    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[]
     */
    protected $synchronizationDataPlugins;

    /**
     * @var int
     */
    protected $chunkSize;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface $queueClient
     * @param \Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface $synchronizationQueueMessageCreator
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[] $synchronizationDataPlugins
     * @param int $chunkSize
     */
    public function __construct(
        SynchronizationToQueueClientInterface $queueClient,
        QueueMessageCreatorInterface $synchronizationQueueMessageCreator,
        array $synchronizationDataPlugins,
        $chunkSize = 100
    ) {
        $this->queueClient = $queueClient;
        $this->queueMessageCreator = $synchronizationQueueMessageCreator;
        $this->synchronizationDataPlugins = $synchronizationDataPlugins;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @param string[] $resources
     * @param int[] $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $resources, array $ids = [])
    {
        $this->mapPluginsByResourceName();
        $plugins = $this->getEffectivePlugins($resources);

        foreach ($plugins as $plugin) {
            if ($plugin instanceof SynchronizationDataQueryContainerPluginInterface) {
                $this->exportDataFromQueryContainer($ids, $plugin);
            }
            if ($plugin instanceof SynchronizationDataRepositoryPluginInterface){
                $this->exportDataFromRepository($ids, $plugin);
            }
        }
    }

    /**
     * @param array $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     *
     * @return void
     */
    protected function exportDataFromQueryContainer(array $ids, SynchronizationDataQueryContainerPluginInterface $plugin)
    {
        $query = $plugin->queryData($ids);
        $count = $query->count();
        $loops = $count / $this->chunkSize;
        $offset = 0;

        for ($i = 0; $i < $loops; $i++) {
            //TODO ordering
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
     * @param array $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     *
     * @return void
     */
    protected function exportDataFromRepository(array $ids, SynchronizationDataRepositoryPluginInterface $plugin)
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
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param array $synchronizationEntities
     *
     * @return void
     */
    protected function syncData(SynchronizationDataPluginInterface $plugin, array $synchronizationEntities)
    {
        $queueSendTransfers = [];
        foreach ($synchronizationEntities as $synchronizedEntity) {
            $store = $this->getStore($plugin->hasStore(), $synchronizedEntity);
            $syncQueueMessage = (new SynchronizationQueueMessageTransfer())
                ->setKey($synchronizedEntity->getKey())
                ->setValue($synchronizedEntity->getData())
                ->setResource($plugin->getResourceName())
                ->setParams($plugin->getParams());

            $queueSendTransfers[] = $this->queueMessageCreator->createQueueMessage($syncQueueMessage, $store, $plugin->getSynchronizationQueuePoolName());
        }

        $this->queueClient->sendMessages($plugin->getQueueName(), $queueSendTransfers);
    }

    /**
     * @param array $resources
     *
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[]
     */
    protected function getEffectivePlugins(array $resources)
    {
        $effectivePlugins = [];
        if (empty($resources)) {
            return $this->synchronizationDataPlugins;
        }

        foreach ($resources as $resource) {
            if (isset($this->synchronizationDataPlugins[$resource])) {
                $effectivePlugins[$resource] = $this->synchronizationDataPlugins[$resource];
            }
        }

        return $effectivePlugins;
    }

    /**
     * @return void
     */
    protected function mapPluginsByResourceName()
    {
        $mappedDataPlugins = [];
        foreach ($this->synchronizationDataPlugins as $plugin) {
            $mappedDataPlugins[$plugin->getResourceName()] = $plugin;
        }

        $this->synchronizationDataPlugins = $mappedDataPlugins;
    }

    /**
     * @param bool $hasStore
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return string|null
     */
    protected function getStore($hasStore, ActiveRecordInterface $entity)
    {
        if ($hasStore) {
            return $entity->getStore();
        }

        return null;
    }
}

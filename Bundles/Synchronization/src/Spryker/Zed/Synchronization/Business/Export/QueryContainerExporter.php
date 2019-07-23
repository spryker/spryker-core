<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Iterator;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataQueryContainerPluginIterator;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

class QueryContainerExporter implements ExporterInterface
{
    protected const DEFAULT_CHUNK_SIZE = 100;

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
     * @param int $chunkSize
     */
    public function __construct(
        SynchronizationToQueueClientInterface $queueClient,
        QueueMessageCreatorInterface $synchronizationQueueMessageCreator,
        $chunkSize
    ) {
        $this->queueClient = $queueClient;
        $this->queueMessageCreator = $synchronizationQueueMessageCreator;
        $this->chunkSize = $chunkSize ?? static::DEFAULT_CHUNK_SIZE;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[] $plugins
     * @param int[] $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $plugins, array $ids = []): void
    {
        foreach ($plugins as $plugin) {
            $this->exportData($ids, $plugin);
        }
    }

    /**
     * @param int[] $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     *
     * @return void
     */
    protected function exportData(array $ids, SynchronizationDataQueryContainerPluginInterface $plugin): void
    {
        foreach ($this->createSynchronizationDataQueryContainerPluginIterator($ids, $plugin) as $synchronizationEntities) {
            $this->syncData($plugin, $synchronizationEntities);
        }
    }

    /**
     * @param int[] $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     *
     * @return \Iterator
     */
    protected function createSynchronizationDataQueryContainerPluginIterator(array $ids, SynchronizationDataQueryContainerPluginInterface $plugin): Iterator
    {
        return new SynchronizationDataQueryContainerPluginIterator($plugin, $this->chunkSize, $ids);
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     * @param array $synchronizationEntities
     *
     * @return void
     */
    protected function syncData(SynchronizationDataQueryContainerPluginInterface $plugin, array $synchronizationEntities): void
    {
        $queueSendTransfers = [];
        foreach ($synchronizationEntities as $synchronizationEntity) {
            $store = $this->getStore($plugin->hasStore(), $synchronizationEntity);
            $syncQueueMessage = (new SynchronizationQueueMessageTransfer())
                ->setKey($synchronizationEntity->getKey())
                ->setValue($synchronizationEntity->getData())
                ->setResource($plugin->getResourceName())
                ->setParams($plugin->getParams());

            $queueSendTransfers[] = $this->queueMessageCreator->createQueueMessage($syncQueueMessage, $plugin, $store);
        }

        $this->queueClient->sendMessages($plugin->getQueueName(), $queueSendTransfers);
    }

    /**
     * @param bool $hasStore
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return string|null
     */
    protected function getStore(bool $hasStore, ActiveRecordInterface $entity): ?string
    {
        if ($hasStore) {
            return $entity->getStore();
        }

        return null;
    }
}

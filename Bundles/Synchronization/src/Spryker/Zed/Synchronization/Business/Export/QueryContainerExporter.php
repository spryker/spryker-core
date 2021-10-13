<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Iterator;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataQueryContainerPluginIterator;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface;

class QueryContainerExporter implements ExporterInterface
{
    use InstancePoolingTrait;

    /**
     * @var int
     */
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
     * @var array<\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface>
     */
    protected $synchronizationDataPlugins;

    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface
     */
    protected $synchronizationDataQueryExpanderStrategyPlugin;

    /**
     * @var int
     */
    protected $chunkSize;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface $queueClient
     * @param \Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface $synchronizationQueueMessageCreator
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface $synchronizationDataQueryExpanderStrategyPlugin
     * @param int $chunkSize
     */
    public function __construct(
        SynchronizationToQueueClientInterface $queueClient,
        QueueMessageCreatorInterface $synchronizationQueueMessageCreator,
        SynchronizationDataQueryExpanderStrategyPluginInterface $synchronizationDataQueryExpanderStrategyPlugin,
        $chunkSize
    ) {
        $this->queueClient = $queueClient;
        $this->queueMessageCreator = $synchronizationQueueMessageCreator;
        $this->synchronizationDataQueryExpanderStrategyPlugin = $synchronizationDataQueryExpanderStrategyPlugin;
        $this->chunkSize = $chunkSize ?? static::DEFAULT_CHUNK_SIZE;
    }

    /**
     * @param array<\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface> $plugins
     * @param array<int> $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $plugins, array $ids = []): void
    {
        $isPoolingStateChanged = $this->disableInstancePooling();

        foreach ($plugins as $plugin) {
            $this->exportData($ids, $plugin);
        }

        if ($isPoolingStateChanged) {
            $this->enableInstancePooling();
        }
    }

    /**
     * @param array<int> $ids
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
     * @param array<int> $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     *
     * @return \Iterator
     */
    protected function createSynchronizationDataQueryContainerPluginIterator(array $ids, SynchronizationDataQueryContainerPluginInterface $plugin): Iterator
    {
        return new SynchronizationDataQueryContainerPluginIterator($plugin, $this->synchronizationDataQueryExpanderStrategyPlugin, $this->chunkSize, $ids);
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     * @param array<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $synchronizationEntities
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

            if (method_exists($synchronizationEntity, 'syncPublishedMessageForMappings')) {
                $synchronizationEntity->syncPublishedMessageForMappings();
            }
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

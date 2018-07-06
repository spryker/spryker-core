<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class RepositoryExporter implements ExporterInterface
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
     * @param int $chunkSize
     */
    public function __construct(
        SynchronizationToQueueClientInterface $queueClient,
        QueueMessageCreatorInterface $synchronizationQueueMessageCreator,
        $chunkSize = 100
    ) {
        $this->queueClient = $queueClient;
        $this->queueMessageCreator = $synchronizationQueueMessageCreator;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @param array $plugins
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
     * @param array $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface $plugin
     *
     * @return void
     */
    protected function exportData(array $ids, SynchronizationDataRepositoryPluginInterface $plugin): void
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
    protected function syncData(SynchronizationDataPluginInterface $plugin, array $synchronizationEntities): void
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
     * @param bool $hasStore
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $entity
     *
     * @return null|string
     */
    protected function getStore(bool $hasStore, SynchronizationDataTransfer $entity): ?string
    {
        if ($hasStore) {
            return $entity->getStore();
        }

        return null;
    }
}

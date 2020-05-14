<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Iterator;
use Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataBulkRepositoryPluginIterator;
use Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataRepositoryPluginIterator;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;
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
     * @var \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface $queueClient
     * @param \Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface $synchronizationQueueMessageCreator
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface $utilEncodingService
     * @param int $chunkSize
     */
    public function __construct(
        SynchronizationToQueueClientInterface $queueClient,
        QueueMessageCreatorInterface $synchronizationQueueMessageCreator,
        SynchronizationToUtilEncodingServiceInterface $utilEncodingService,
        $chunkSize = 100
    ) {
        $this->queueClient = $queueClient;
        $this->queueMessageCreator = $synchronizationQueueMessageCreator;
        $this->chunkSize = $chunkSize;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface[]|\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface[] $plugins
     * @param int[] $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $plugins, array $ids = []): void
    {
        foreach ($plugins as $plugin) {
            if ($plugin instanceof SynchronizationDataRepositoryPluginInterface) {
                $this->exportData($ids, $plugin);

                continue;
            }

            if ($plugin instanceof SynchronizationDataBulkRepositoryPluginInterface) {
                $this->exportDataBulk($plugin, $ids);
            }
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
        foreach ($this->createSynchronizationDataRepositoryPluginIterator($ids, $plugin) as $synchronizationEntityTransfers) {
            $this->syncData($plugin, $synchronizationEntityTransfers);
        }
    }

    /**
     * @param array $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface $plugin
     *
     * @return \Iterator
     */
    protected function createSynchronizationDataRepositoryPluginIterator(array $ids, SynchronizationDataRepositoryPluginInterface $plugin): Iterator
    {
        return new SynchronizationDataRepositoryPluginIterator($plugin, $this->chunkSize, $ids);
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface $plugin
     * @param int[] $ids
     *
     * @return void
     */
    protected function exportDataBulk(SynchronizationDataBulkRepositoryPluginInterface $plugin, array $ids = []): void
    {
        foreach ($this->createSynchronizationDataBulkRepositoryPluginIterator($ids, $plugin) as $synchronizationEntityTransfers) {
            $this->syncData($plugin, $synchronizationEntityTransfers);
        }
    }

    /**
     * @param int[] $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface $plugin
     *
     * @return \Iterator
     */
    protected function createSynchronizationDataBulkRepositoryPluginIterator(array $ids, SynchronizationDataBulkRepositoryPluginInterface $plugin): Iterator
    {
        return new SynchronizationDataBulkRepositoryPluginIterator($plugin, $this->chunkSize, $ids);
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

            foreach ($this->getSynchronizationKeys($synchronizedEntity) as $synchronizationKey) {
                $syncQueueMessage = (new SynchronizationQueueMessageTransfer())
                    ->setKey($synchronizationKey)
                    ->setValue($synchronizedEntity->getData())
                    ->setResource($plugin->getResourceName())
                    ->setParams($plugin->getParams());

                $queueSendTransfers[] = $this->queueMessageCreator->createQueueMessage($syncQueueMessage, $plugin, $store);
            }
        }

        $this->queueClient->sendMessages($plugin->getQueueName(), $queueSendTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return array
     */
    protected function getSynchronizationKeys(SynchronizationDataTransfer $synchronizationDataTransfer): array
    {
        $synchronizationKeys[] = $synchronizationDataTransfer->getKey();

        if ($synchronizationDataTransfer->getAliasKeys()) {
            $decodedAliasKeys = $this->utilEncodingService->decodeJson($synchronizationDataTransfer->getAliasKeys(), true);
            $synchronizationKeys = array_merge($synchronizationKeys, array_keys($decodedAliasKeys));
        }

        return array_unique($synchronizationKeys);
    }

    /**
     * @param bool $hasStore
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $entity
     *
     * @return string|null
     */
    protected function getStore(bool $hasStore, SynchronizationDataTransfer $entity): ?string
    {
        if ($hasStore) {
            return $entity->getStore();
        }

        return null;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Iterator;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataBulkRepositoryPluginIterator;
use Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataRepositoryPluginIterator;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\Synchronization\Dependency\Facade\SynchronizationToStoreFacadeInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;
use Spryker\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class RepositoryExporter implements ExporterInterface
{
    use InstancePoolingTrait;

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
     * @var \Spryker\Zed\Synchronization\SynchronizationConfig
     */
    protected SynchronizationConfig $synchronizationConfig;

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Facade\SynchronizationToStoreFacadeInterface
     */
    protected SynchronizationToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface $queueClient
     * @param \Spryker\Zed\Synchronization\Dependency\Facade\SynchronizationToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface $synchronizationQueueMessageCreator
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\Synchronization\SynchronizationConfig $synchronizationConfig
     */
    public function __construct(
        SynchronizationToQueueClientInterface $queueClient,
        SynchronizationToStoreFacadeInterface $storeFacade,
        QueueMessageCreatorInterface $synchronizationQueueMessageCreator,
        SynchronizationToUtilEncodingServiceInterface $utilEncodingService,
        SynchronizationConfig $synchronizationConfig
    ) {
        $this->queueClient = $queueClient;
        $this->queueMessageCreator = $synchronizationQueueMessageCreator;
        $this->utilEncodingService = $utilEncodingService;
        $this->storeFacade = $storeFacade;
        $this->synchronizationConfig = $synchronizationConfig;
    }

    /**
     * @param array<\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface|\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface> $plugins
     * @param array<int> $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $plugins, array $ids = []): void
    {
        $isInstancePoolingDisabled = false;
        if ($this->synchronizationConfig->isRepositorySyncExportPropelInstancePoolingDisabled()) {
            $isInstancePoolingDisabled = $this->disableInstancePooling();
        }

        foreach ($plugins as $plugin) {
            if ($plugin instanceof SynchronizationDataRepositoryPluginInterface) {
                $this->exportData($ids, $plugin);

                continue;
            }

            if ($plugin instanceof SynchronizationDataBulkRepositoryPluginInterface) {
                $this->exportDataBulk($plugin, $ids);
            }
        }

        if ($isInstancePoolingDisabled) {
            $this->enableInstancePooling();
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
        return new SynchronizationDataRepositoryPluginIterator(
            $plugin,
            $this->synchronizationConfig->getSyncExportChunkSize(),
            $ids,
        );
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface $plugin
     * @param array<int> $ids
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
     * @param array<int> $ids
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface $plugin
     *
     * @return \Iterator
     */
    protected function createSynchronizationDataBulkRepositoryPluginIterator(array $ids, SynchronizationDataBulkRepositoryPluginInterface $plugin): Iterator
    {
        return new SynchronizationDataBulkRepositoryPluginIterator(
            $plugin,
            $this->synchronizationConfig->getSyncExportChunkSize(),
            $ids,
        );
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param array $synchronizationEntities
     *
     * @return void
     */
    protected function syncData(SynchronizationDataPluginInterface $plugin, array $synchronizationEntities): void
    {
        $queueSendMessageTransfers = [];
        foreach ($synchronizationEntities as $synchronizationEntity) {
            $store = $this->getStore($plugin->hasStore(), $synchronizationEntity);
            $synchronizationQueueMessageTransfers = $this->getSynchronizationQueueMessageTransfers($plugin, $synchronizationEntity, $store);

            foreach ($synchronizationQueueMessageTransfers as $synchronizationQueueMessageTransfer) {
                $queueSendMessageTransfers[] = $this->queueMessageCreator->createQueueMessage(
                    $synchronizationQueueMessageTransfer,
                    $plugin,
                    $store,
                );
            }
        }

        $this->queueClient->sendMessages($plugin->getQueueName(), $queueSendMessageTransfers);
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

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     * @param string|null $store
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationQueueMessageTransfer>
     */
    protected function getSynchronizationQueueMessageTransfers(
        SynchronizationDataPluginInterface $plugin,
        SynchronizationDataTransfer $synchronizationDataTransfer,
        ?string $store = null
    ): array {
        $synchronizationQueueMessageTransfers = [];
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if ($store || !$this->storeFacade->isDynamicStoreEnabled()) {
            return $this->createSynchronizationQueueMessageTransfers(
                $plugin,
                $synchronizationDataTransfer,
                $synchronizationQueueMessageTransfers,
                $store,
            );
        }

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $synchronizationQueueMessageTransfers = $this->createSynchronizationQueueMessageTransfers(
                $plugin,
                $synchronizationDataTransfer,
                $synchronizationQueueMessageTransfers,
                $storeTransfer->getNameOrFail(),
            );
        }

        return $synchronizationQueueMessageTransfers;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     * @param array<\Generated\Shared\Transfer\SynchronizationQueueMessageTransfer> $synchronizationQueueMessageTransfers
     * @param string|null $store
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationQueueMessageTransfer>
     */
    protected function createSynchronizationQueueMessageTransfers(
        SynchronizationDataPluginInterface $plugin,
        SynchronizationDataTransfer $synchronizationDataTransfer,
        array $synchronizationQueueMessageTransfers,
        ?string $store = null
    ): array {
        /** @var array $data */
        $data = $synchronizationDataTransfer->getData();

        $synchronizationQueueMessageTransfers[] = $this->createSynchronizationQueueMessageTransfer(
            $plugin,
            $synchronizationDataTransfer->getKey(),
            $data,
            $store,
        );

        foreach ($this->getSynchronizationMappings($synchronizationDataTransfer) as $mappingKey => $mappingData) {
            $synchronizationQueueMessageTransfers[] = $this->createSynchronizationQueueMessageTransfer($plugin, $mappingKey, $mappingData, $store);
        }

        return $synchronizationQueueMessageTransfers;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param string $synchronizationKey
     * @param array<string, mixed> $data
     * @param string|null $store
     *
     * @return \Generated\Shared\Transfer\SynchronizationQueueMessageTransfer
     */
    protected function createSynchronizationQueueMessageTransfer(
        SynchronizationDataPluginInterface $plugin,
        string $synchronizationKey,
        array $data,
        ?string $store = null
    ): SynchronizationQueueMessageTransfer {
        return (new SynchronizationQueueMessageTransfer())
            ->setKey($synchronizationKey)
            ->setValue($data)
            ->setResource($plugin->getResourceName())
            ->setParams($plugin->getParams())
            ->setStore($store);
    }

    /**
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $synchronizationDataTransfer
     *
     * @return array
     */
    protected function getSynchronizationMappings(SynchronizationDataTransfer $synchronizationDataTransfer): array
    {
        $synchronizationMappings = [];

        if ($synchronizationDataTransfer->getAliasKeys()) {
            $synchronizationMappings = $this->utilEncodingService->decodeJson($synchronizationDataTransfer->getAliasKeys(), true);
        }

        return $synchronizationMappings;
    }
}

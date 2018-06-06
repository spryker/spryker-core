<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Client\Queue\QueueClient;
use Spryker\Zed\Synchronization\Business\Exception\SynchronizationQueuePoolNotFoundException;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;

class Exporter implements ExporterInterface
{
    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]
     */
    protected $synchronizationDataPlugins;

    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[] $synchronizationDataPlugins
     */
    public function __construct(array $synchronizationDataPlugins)
    {
        $this->synchronizationDataPlugins = $synchronizationDataPlugins;
        /*
         *  TODO fix this
         */
        $this->queueClient = new QueueClient();
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
            $synchronizationEntities = $plugin->queryData($ids)->find()->getData();
            $this->syncData($plugin, $synchronizationEntities);
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
        foreach ($synchronizationEntities as $synchronizedEntity) {
            $store = $this->getStore($plugin->hasStore(), $synchronizedEntity);
            $message = $this->createMessageBody(
                $synchronizedEntity->getData(),
                $synchronizedEntity->getKey(),
                $plugin->getResourceName(),
                $plugin->getParams()
            );

            $queueSendTransfer = $this->createQueueSendMessageTransfer($message, $store, $plugin->getSynchronizationQueuePoolName());
            $this->queueClient->sendMessage($plugin->getQueueName(), $queueSendTransfer);
        }
    }

    /**
     * @param array $resources
     *
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]
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
     * @param array $message
     * @param string|null $store
     * @param string|null $queuePoolName
     *
     * @throws \Spryker\Zed\Synchronization\Business\Exception\SynchronizationQueuePoolNotFoundException
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    protected function createQueueSendMessageTransfer(array $message, $store = null, $queuePoolName = null)
    {
        $queueSendTransfer = new QueueSendMessageTransfer();
        $queueSendTransfer->setBody(json_encode($message));

        if ($store) {
            $queueSendTransfer->setStoreName($store);

            return $queueSendTransfer;
        }

        if (!$queuePoolName) {
            throw new SynchronizationQueuePoolNotFoundException('You must either have store column or `SynchronizationQueuePoolName` in schema.xml defined');
        }
        $queueSendTransfer->setQueuePoolName($queuePoolName);

        return $queueSendTransfer;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $resource
     * @param array $params
     *
     * @return array
     */
    protected function createMessageBody(array $data, $key, $resource, array $params = [])
    {
        $message = [
            'write' => [
                'key' => $key,
                'value' => $data,
                'resource' => $resource,
                'params' => $params,
            ],
        ];

        return $message;
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

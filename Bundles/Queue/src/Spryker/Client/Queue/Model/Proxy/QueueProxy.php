<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Proxy;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\Queue\Exception\MissingQueueAdapterException;
use Spryker\Shared\Queue\QueueConfig;

class QueueProxy implements QueueProxyInterface
{
    /**
     * @var \Spryker\Client\Queue\Model\Adapter\AdapterInterface[]
     */
    protected $queueAdapters;

    /**
     * @var array
     */
    protected $queueConfiguration;

    /**
     * @var array
     */
    protected $queueDefaultConfiguration;

    /**
     * @var array
     */
    protected static $queueAdapterCache = [];

    /**
     * @param \Spryker\Client\Queue\Model\Adapter\AdapterInterface[] $queueAdapters
     * @param array $queueConfiguration
     * @param array $queueDefaultConfiguration
     */
    public function __construct(array $queueAdapters, array $queueConfiguration, array $queueDefaultConfiguration)
    {
        $this->queueAdapters = $queueAdapters;
        $this->queueConfiguration = $queueConfiguration;
        $this->queueDefaultConfiguration = $queueDefaultConfiguration;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = [])
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        return $queueAdapter->createQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer)
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        $queueAdapter->sendMessage($queueName, $queueSendMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer[] $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers)
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        $queueAdapter->sendMessages($queueName, $queueSendMessageTransfers);
    }

    /**
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function receiveMessages($queueName, $chunkSize = 100, array $options = [])
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        return $queueAdapter->receiveMessages($queueName, $chunkSize, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = [])
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        return $queueAdapter->receiveMessage($queueName, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        $queueAdapter = $this->getQueueAdapter($queueReceiveMessageTransfer->getQueueName());

        $queueAdapter->acknowledge($queueReceiveMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        $queueAdapter = $this->getQueueAdapter($queueReceiveMessageTransfer->getQueueName());

        $queueAdapter->reject($queueReceiveMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        $queueAdapter = $this->getQueueAdapter($queueReceiveMessageTransfer->getQueueName());

        return $queueAdapter->handleError($queueReceiveMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = [])
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        return $queueAdapter->purgeQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = [])
    {
        $queueAdapter = $this->getQueueAdapter($queueName);

        return $queueAdapter->deleteQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     *
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    protected function getQueueAdapter($queueName)
    {
        if (isset(static::$queueAdapterCache[$queueName])) {
            return static::$queueAdapterCache[$queueName];
        }

        $queueConfiguration = $this->getQueueConfiguration($queueName);
        static::$queueAdapterCache[$queueName] = $this->getConfigQueueAdapter($queueConfiguration);

        return static::$queueAdapterCache[$queueName];
    }

    /**
     * @param string $queueName
     *
     * @throws \Spryker\Client\Queue\Exception\MissingQueueAdapterException
     *
     * @return array
     */
    protected function getQueueConfiguration($queueName)
    {
        if (array_key_exists($queueName, $this->queueConfiguration)) {
            return $this->queueConfiguration[$queueName];
        }

        if (!empty($this->queueDefaultConfiguration)) {
            return $this->queueDefaultConfiguration;
        }

        throw new MissingQueueAdapterException(
            sprintf(
                'There is no queue adapter configuration with this name: %s ,' .
                ' you can fix this by adding the queue adapter in ' .
                'QUEUE_ADAPTER_CONFIGURATION in the config_default.php',
                $queueName
            )
        );
    }

    /**
     * @param array $queueConfiguration
     *
     * @throws \Spryker\Client\Queue\Exception\MissingQueueAdapterException
     *
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    protected function getConfigQueueAdapter(array $queueConfiguration)
    {
        foreach ($this->queueAdapters as $queueAdapter) {
            if (get_class($queueAdapter) === $queueConfiguration[QueueConfig::CONFIG_QUEUE_ADAPTER]) {
                return $queueAdapter;
            }
        }

        throw new MissingQueueAdapterException(
            sprintf(
                'There is no such a queue adapter with this name: %s ,' .
                ' you can fix this by adding the queue adapter in ' .
                'QueueDependencyProvider::createQueueAdapters()',
                $queueConfiguration[QueueConfig::CONFIG_QUEUE_ADAPTER]
            )
        );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Model\Storage;

use Spryker\Shared\Synchronization\SynchronizationConfig as SharedSynchronizationConfig;
use Spryker\Zed\Synchronization\Business\Model\AbstractSynchronization;
use Spryker\Zed\Synchronization\Business\Model\SynchronizationInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface;
use Spryker\Zed\Synchronization\SynchronizationConfig;

class SynchronizationStorage extends AbstractSynchronization implements SynchronizationInterface
{

    const KEY = 'key';
    const VALUE = 'value';

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageInterface $storageClient
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Synchronization\SynchronizationConfig $config
     */
    public function __construct(SynchronizationToStorageInterface $storageClient, SynchronizationToUtilEncodingInterface $utilEncodingService, SynchronizationConfig $config)
    {
        parent::__construct($utilEncodingService, $config);

        $this->storageClient = $storageClient;
    }

    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function write(array $data, $queueName)
    {
        $key = $data[static::KEY];
        $value = $data[static::VALUE];

        if ($this->isInvalid($queueName, $key, $value)) {
            return;
        }

        $this->set($key, $value);
    }

    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function delete(array $data, $queueName)
    {
        $key = $data[static::KEY];
        $value = $data[static::VALUE];

        if ($this->isInvalid($queueName, $key, $value)) {
            return;
        }

        $this->del($key);
    }

    /**
     * @param string $key
     * @param array $value
     *
     * @return void
     */
    protected function set($key, array $value)
    {
        $this->storageClient->set($key, $this->getEncodedValue($value), null, SharedSynchronizationConfig::SYNCHRONIZATION_STORAGE_PREFIX);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function del($key)
    {
        $this->storageClient->delete($key, SharedSynchronizationConfig::SYNCHRONIZATION_STORAGE_PREFIX);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function getExistEntryByKey($key)
    {
        return $this->storageClient->get($key, SharedSynchronizationConfig::SYNCHRONIZATION_STORAGE_PREFIX);
    }

}

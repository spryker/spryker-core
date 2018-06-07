<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Dependency\Client;

class SynchronizationToStorageClientBridge implements SynchronizationToStorageClientInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * SynchronizationToStorageBridge constructor.
     *
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct($storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return int
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->storageClient->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function get($key)
    {
        return $this->storageClient->get($key);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        $this->storageClient->delete($key);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Dependency\Client;

class SynchronizationToStorageBridge implements SynchronizationToStorageInterface
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
     * @param string $prefix
     *
     * @return int
     */
    public function set($key, $value, $ttl = null, $prefix = '')
    {
        return $this->storageClient->set($key, $value, $ttl, $prefix);
    }

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return array
     */
    public function get($key, $prefix = '')
    {
        return $this->storageClient->get($key, $prefix);
    }

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return void
     */
    public function delete($key, $prefix = '')
    {
        $this->storageClient->delete($key, $prefix);
    }

}

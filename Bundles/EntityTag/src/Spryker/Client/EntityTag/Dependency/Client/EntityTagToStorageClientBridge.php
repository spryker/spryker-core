<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag\Dependency\Client;

class EntityTagToStorageClientBridge implements EntityTagToStorageClientInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct($storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->storageClient->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->storageClient->set($key, $value, $ttl);
    }
}

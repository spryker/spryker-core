<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Dependency\Client;

class ServicePointStorageToStorageClientBridge implements ServicePointStorageToStorageClientInterface
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
     * @param list<string> $keys
     *
     * @return list<string>
     */
    public function getMulti(array $keys): array
    {
        return $this->storageClient->getMulti($keys);
    }
}

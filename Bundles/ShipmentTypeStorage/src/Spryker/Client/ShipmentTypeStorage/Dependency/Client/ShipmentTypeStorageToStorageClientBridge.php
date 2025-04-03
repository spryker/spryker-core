<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Dependency\Client;

use Generated\Shared\Transfer\StorageScanResultTransfer;

class ShipmentTypeStorageToStorageClientBridge implements ShipmentTypeStorageToStorageClientInterface
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
     * @return array<string, string|null>
     */
    public function getMulti(array $keys): array
    {
        return $this->storageClient->getMulti($keys);
    }

    /**
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer
    {
        return $this->storageClient->scanKeys($pattern, $limit, $cursor);
    }

    /**
     * @param string $pattern
     *
     * @return array<string, string|null>
     */
    public function getKeys(string $pattern): array
    {
        return $this->storageClient->getKeys($pattern);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->storageClient->get($key);
    }
}

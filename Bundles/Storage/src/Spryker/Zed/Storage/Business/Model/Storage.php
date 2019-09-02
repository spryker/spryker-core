<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business\Model;

use Spryker\Client\Storage\StorageClientInterface;

class Storage implements StorageInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
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
     * @return int
     */
    public function getTotalCount()
    {
        return $this->storageClient->getCountItems();
    }

    /**
     * @return array
     */
    public function getTimestamps()
    {
        $timestampData = [];
        $timestampKeys = $this->storageClient->getKeys('kv:*.timestamp');
        foreach ($timestampKeys as $timestampKey) {
            $timestampKey = str_replace('kv:', '', $timestampKey);
            $timestampData[$timestampKey] = $this->storageClient->get($timestampKey);
        }

        return $timestampData;
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        return $this->storageClient->deleteAll();
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->storageClient->deleteMulti($keys);
    }
}

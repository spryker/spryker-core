<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Business\Model;

use Spryker\Client\Storage\StorageClient;

class Storage
{

    /**
     * @var StorageClient
     */
    private $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClient $storageClient
     */
    public function __construct(StorageClient $storageClient)
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
     * return int
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
        $metaData = [];

        $allKeys = $this->storageClient->getAllKeys();
        foreach ($allKeys as $key) {
            $key = str_replace('kv:', '', $key);

            if (strpos($key, '.timestamp') !== false) {
                $metaData[$key] = $this->storageClient->get($key);
            }
        }

        return $metaData;
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

<?php

namespace Spryker\Client\Category\Dependency\Client;


class CategoryToStorageClientBridge implements CategoryToStorageClientInterface
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
     * @return string
     */
    public function get($key)
    {
        return $this->storageClient->get($key);
    }

}
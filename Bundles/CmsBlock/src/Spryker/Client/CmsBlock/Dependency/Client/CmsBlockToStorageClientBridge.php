<?php

namespace Spryker\Client\CmsBlock;


use Spryker\Client\Storage\StorageClientInterface;

class CmsBlockToStorageClientBridge implements CmsBlockToStorageClientInterface
{

    /**
     * @var StorageClientInterface
     */
    protected $storage;

    /**
     * @param StorageClientInterface $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti($keys)
    {
        return $this->storage->getMulti($keys);
    }

}
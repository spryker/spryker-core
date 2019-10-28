<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet\Dependency\Client;

class ProductSetToStorageBridge implements ProductSetToStorageInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $localeClient
     */
    public function __construct($localeClient)
    {
        $this->storageClient = $localeClient;
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
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        return $this->storageClient->getMulti($keys);
    }
}

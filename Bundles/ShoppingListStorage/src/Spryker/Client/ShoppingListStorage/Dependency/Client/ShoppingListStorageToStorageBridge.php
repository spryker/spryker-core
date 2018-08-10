<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\Dependency\Client;

class ShoppingListStorageToStorageBridge implements ShoppingListStorageToStorageInterface
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storage;

    /**
     * @api
     *
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->storage->get($key);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        return $this->storage->getMulti($keys);
    }
}

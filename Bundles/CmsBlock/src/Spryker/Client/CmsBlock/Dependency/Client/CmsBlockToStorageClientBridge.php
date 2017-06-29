<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\Dependency\Client;

class CmsBlockToStorageClientBridge implements CmsBlockToStorageClientInterface
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
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti($keys)
    {
        return $this->storage->getMulti($keys);
    }

}

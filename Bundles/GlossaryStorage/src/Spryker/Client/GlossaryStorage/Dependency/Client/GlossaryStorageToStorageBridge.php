<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Dependency\Client;

class GlossaryStorageToStorageBridge implements GlossaryStorageToStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * SynchronizationToStorageBridge constructor.
     *
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct($storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return array
     */
    public function get($key, $prefix = '')
    {
        return $this->storageClient->get($key, $prefix);
    }

}

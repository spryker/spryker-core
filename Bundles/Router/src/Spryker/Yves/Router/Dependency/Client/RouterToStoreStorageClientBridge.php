<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Dependency\Client;

class RouterToStoreStorageClientBridge implements RouterToStoreStorageClientInterface
{
    /**
     * @var \Spryker\Client\StoreStorage\StoreStorageClientInterface
     */
    protected $storeStorageClient;

    /**
     * @param \Spryker\Client\StoreStorage\StoreStorageClientInterface $storeStorageClient
     */
    public function __construct($storeStorageClient)
    {
        $this->storeStorageClient = $storeStorageClient;
    }

    /**
     * @return string[]
     */
    public function getAllStores(): array
    {
        return $this->storeStorageClient->getAllStores();
    }
}

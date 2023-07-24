<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Dependency\Client;

use Generated\Shared\Transfer\StoreStorageTransfer;
use Spryker\Client\StoreStorage\StoreStorageClientInterface;

class StoresApiToStoreStorageClientBridge implements StoresApiToStoreStorageClientInterface
{
    /**
     * @var \Spryker\Client\StoreStorage\StoreStorageClientInterface
     */
    protected StoreStorageClientInterface $storeStorageClient;

    /**
     * @param \Spryker\Client\StoreStorage\StoreStorageClientInterface $storeStorageClient
     */
    public function __construct($storeStorageClient)
    {
        $this->storeStorageClient = $storeStorageClient;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer|null
     */
    public function findStoreByName(string $name): ?StoreStorageTransfer
    {
        return $this->storeStorageClient->findStoreByName($name);
    }

    /**
     * @return array<string>
     */
    public function getStoreNames(): array
    {
        return $this->storeStorageClient->getStoreNames();
    }
}

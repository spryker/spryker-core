<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

class TaxAppToStoreClientBridge implements TaxAppToStoreClientInterface
{
    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct($storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName(string $storeName): StoreTransfer
    {
        return $this->storeClient->getStoreByName($storeName);
    }
}

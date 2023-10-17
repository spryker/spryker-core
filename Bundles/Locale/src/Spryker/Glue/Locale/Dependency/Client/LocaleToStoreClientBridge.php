<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale\Dependency\Client;

class LocaleToStoreClientBridge implements LocaleToStoreClientInterface
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
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool
    {
        return $this->storeClient->isDynamicStoreEnabled();
    }
}

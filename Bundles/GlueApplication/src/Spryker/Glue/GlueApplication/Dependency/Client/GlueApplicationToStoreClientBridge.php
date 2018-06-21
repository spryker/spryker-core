<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

class GlueApplicationToStoreClientBridge implements GlueApplicationToStoreClientInterface
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
     * @return \Generated\Shared\Transfer\StoreTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCurrentStore(): StoreTransfer
    {
        return $this->storeClient->getCurrentStore();
    }
}

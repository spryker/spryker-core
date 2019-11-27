<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Store\StoreFactory getFactory()
 */
class StoreClient extends AbstractClient implements StoreClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->getFactory()
            ->createStoreReader()
            ->getCurrentStore();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName(string $storeName): StoreTransfer
    {
        return $this->getFactory()
            ->createStoreReader()
            ->getStoreByName($storeName);
    }
}

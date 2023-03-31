<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage;

use Generated\Shared\Transfer\StoreStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\StoreStorage\StoreStorageFactory getFactory()
 */
class StoreStorageClient extends AbstractClient implements StoreStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer|null
     */
    public function findStoreByName(string $name): ?StoreStorageTransfer
    {
        return $this->getFactory()
            ->createStoreStorageReader()
            ->findStoreByName($name);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getStoreNames(): array
    {
        return $this->getFactory()
            ->createStoreListReader()
            ->getStoresNames();
    }
}

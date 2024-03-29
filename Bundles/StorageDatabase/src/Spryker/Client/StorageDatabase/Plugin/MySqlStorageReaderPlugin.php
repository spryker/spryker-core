<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseFactory getFactory()
 */
class MySqlStorageReaderPlugin extends AbstractPlugin implements StorageReaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Fetches the data from MySql storage database.
     *
     * @api
     *
     * @param string $resourceKey
     *
     * @return string
     */
    public function get(string $resourceKey): string
    {
        return $this->getFactory()->createMySqlStorageReader()->get($resourceKey);
    }

    /**
     * {@inheritDoc}
     * - Fetches the data from MySql storage database.
     *
     * @api
     *
     * @param array<string> $resourceKeys
     *
     * @return array
     */
    public function getMulti(array $resourceKeys): array
    {
        return $this->getFactory()->createMySqlStorageReader()->getMulti($resourceKeys);
    }
}

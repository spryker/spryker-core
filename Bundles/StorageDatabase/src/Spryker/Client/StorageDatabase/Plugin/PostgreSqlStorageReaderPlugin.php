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
class PostgreSqlStorageReaderPlugin extends AbstractPlugin implements StorageReaderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Fetches the data from PostgresSql storage database.
     *
     * @api
     *
     * @param string $resourceKey
     *
     * @return string
     */
    public function get(string $resourceKey): string
    {
        return $this->getFactory()->createPostgreSqlStorageReader()->get($resourceKey);
    }

    /**
     * {@inheritdoc}
     * - Fetches the data from PostgresSql storage database.
     *
     * @api
     *
     * @param string[] $resourceKeys
     *
     * @return array
     */
    public function getMulti(array $resourceKeys): array
    {
        return $this->getFactory()->createPostgreSqlStorageReader()->getMulti($resourceKeys);
    }
}

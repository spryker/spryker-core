<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderProviderPluginInterface;
use Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseFactory getFactory()
 */
class PostgreSqlStorageReaderProviderPlugin extends AbstractPlugin implements StorageReaderProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface
     */
    public function getStorageReader(): StorageReaderInterface
    {
        return $this->getFactory()->createPostgreSqlStorageReader();
    }
}

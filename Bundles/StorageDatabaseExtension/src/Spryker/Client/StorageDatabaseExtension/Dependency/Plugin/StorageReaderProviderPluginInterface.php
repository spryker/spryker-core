<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabaseExtension\Dependency\Plugin;

use Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface;

interface StorageReaderProviderPluginInterface
{
    /**
     * Specification:
     * - Returns an instance of storage database reader.
     *
     * @api
     *
     * @return \Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface
     */
    public function getStorageReader(): StorageReaderInterface;
}

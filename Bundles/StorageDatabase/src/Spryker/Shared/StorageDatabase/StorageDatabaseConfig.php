<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\StorageDatabase;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class StorageDatabaseConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const DB_ENGINE_PGSQL = 'pgsql';

    /**
     * @var string
     */
    public const DB_ENGINE_MYSQL = 'mysql';

    /**
     * @var string
     */
    public const KEY_STORAGE_TABLE_PREFIX = 'KEY_STORAGE_TABLE_PREFIX';

    /**
     * @var string
     */
    public const KEY_STORAGE_TABLE_SUFFIX = 'KEY_STORAGE_TABLE_SUFFIX';

    /**
     * @var string
     */
    public const KEY_STORAGE_TABLE_NAME = 'KEY_STORAGE_TABLE_NAME';
}

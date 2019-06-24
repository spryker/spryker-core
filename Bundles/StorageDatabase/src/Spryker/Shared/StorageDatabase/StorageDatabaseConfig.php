<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\StorageDatabase;

class StorageDatabaseConfig
{
    /**
     * @uses `\Spryker\Zed\Propel\PropelConfig::DB_ENGINE_PGSQL`
     */
    public const DB_ENGINE_PGSQL = 'pgsql';

    /**
     * @uses \Spryker\Zed\Propel\PropelConfig::DB_ENGINE_MYSQL
     */
    public const DB_ENGINE_MYSQL = 'mysql';

    public const KEY_STORAGE_TABLE_PREFIX = 'KEY_STORAGE_TABLE_PREFIX';
    public const KEY_STORAGE_TABLE_SUFFIX = 'KEY_STORAGE_TABLE_SUFFIX';
    public const KEY_STORAGE_TABLE_NAME = 'KEY_STORAGE_TABLE_NAME';

    /**
     * @return string
     */
    public function getPostgreSqlDbEngineName(): string
    {
        return static::DB_ENGINE_PGSQL;
    }

    /**
     * @return string
     */
    public function getMySqlDbEngineName(): string
    {
        return static::DB_ENGINE_MYSQL;
    }

    /**
     * @return string
     */
    public function getStorageTablePrefixConfigKey(): string
    {
        return static::KEY_STORAGE_TABLE_PREFIX;
    }

    /**
     * @return string
     */
    public function getStorageTableSuffixConfigKey(): string
    {
        return static::KEY_STORAGE_TABLE_SUFFIX;
    }

    /**
     * @return string
     */
    public function getStorageTableNameConfigKey(): string
    {
        return static::KEY_STORAGE_TABLE_NAME;
    }
}

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
}

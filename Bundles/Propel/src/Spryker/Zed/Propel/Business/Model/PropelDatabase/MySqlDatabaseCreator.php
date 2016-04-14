<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;

class MySqlDatabaseCreator implements DatabaseCreatorInterface
{

    /**
     * @return string
     */
    public function getEngine()
    {
        return PropelConfig::DB_ENGINE_MYSQL;
    }

    /**
     * @return void
     */
    public function createIfNotExists()
    {
        $this->getConnection()->exec($this->getQuery());
    }

    /**
     * @return \PDO
     */
    protected function getConnection()
    {
        return new \PDO(
            $this->getDatabaseSourceName(),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            Config::get(PropelConstants::ZED_DB_PASSWORD)
        );
    }

    /**
     * @return string
     */
    protected function getDatabaseSourceName()
    {
        return Config::get(PropelConstants::ZED_DB_ENGINE)
            . ':host='
            . Config::get(PropelConstants::ZED_DB_HOST)
            . ';port=' . Config::get(PropelConstants::ZED_DB_PORT);
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return 'CREATE DATABASE IF NOT EXISTS ' . Config::get(PropelConstants::ZED_DB_DATABASE) . ' CHARACTER SET "utf8"';
    }

}

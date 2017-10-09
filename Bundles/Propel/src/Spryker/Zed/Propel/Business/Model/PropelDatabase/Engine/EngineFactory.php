<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\CreateMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\DropMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\ExportMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\ImportMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\CreatePostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\DropPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\ExportPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\ImportPostgreSqlDatabase;
use Spryker\Zed\Propel\PropelConfig;

class EngineFactory implements EngineFactoryInterface
{

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface
     */
    public function createMySqlEngine()
    {
        $mySqlEngine = new Engine(
            PropelConfig::DB_ENGINE_MYSQL,
            $this->createMySqlCreateCommand(),
            $this->createMySqlDropCommand(),
            $this->createMySqlExportCommand(),
            $this->createMySqlImportCommand()
        );

        return $mySqlEngine;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\CreateMySqlDatabase
     */
    protected function createMySqlCreateCommand()
    {
        return new CreateMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\DropMySqlDatabase
     */
    protected function createMySqlDropCommand()
    {
        return new DropMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\ExportMySqlDatabase
     */
    protected function createMySqlExportCommand()
    {
        return new ExportMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\MySql\ImportMySqlDatabase
     */
    protected function createMySqlImportCommand()
    {
        return new ImportMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\EngineInterface
     */
    public function createPostgreSqlEngine()
    {
        $postgreSqlEngine = new Engine(
            PropelConfig::DB_ENGINE_PGSQL,
            $this->createPostgreSqlCreateCommand(),
            $this->createPostgreSqlDropCommand(),
            $this->createPostgreSqlExportCommand(),
            $this->createPostgreSqlImportCommand()
        );

        return $postgreSqlEngine;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\CreatePostgreSqlDatabase
     */
    protected function createPostgreSqlCreateCommand()
    {
        return new CreatePostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\DropPostgreSqlDatabase
     */
    protected function createPostgreSqlDropCommand()
    {
        return new DropPostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\ExportPostgreSqlDatabase
     */
    protected function createPostgreSqlExportCommand()
    {
        return new ExportPostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql\ImportPostgreSqlDatabase
     */
    protected function createPostgreSqlImportCommand()
    {
        return new ImportPostgreSqlDatabase();
    }

}

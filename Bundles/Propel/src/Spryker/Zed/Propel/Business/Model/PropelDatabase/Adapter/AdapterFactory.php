<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\CleanMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\CreateMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\DropMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\ExportMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\ImportMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\CleanPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\CreatePostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\DropPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\ExportPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\ImportPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CleanDatabaseInterface;
use Spryker\Zed\Propel\PropelConfig;

class AdapterFactory implements AdapterFactoryInterface
{
    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterInterface
     */
    public function createMySqlAdapter()
    {
        $mySqlAdapter = new Adapter(
            PropelConfig::DB_ENGINE_MYSQL,
            $this->createMySqlCreateCommand(),
            $this->createMySqlDropCommand(),
            $this->createMySqlExportCommand(),
            $this->createMySqlImportCommand(),
            $this->createMySqlCleanCommand()
        );

        return $mySqlAdapter;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\CreateMySqlDatabase
     */
    protected function createMySqlCreateCommand()
    {
        return new CreateMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\DropMySqlDatabase
     */
    protected function createMySqlDropCommand()
    {
        return new DropMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\ExportMySqlDatabase
     */
    protected function createMySqlExportCommand()
    {
        return new ExportMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\ImportMySqlDatabase
     */
    protected function createMySqlImportCommand()
    {
        return new ImportMySqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterInterface
     */
    public function createPostgreSqlAdapter()
    {
        $postgreSqlAdapter = new Adapter(
            PropelConfig::DB_ENGINE_PGSQL,
            $this->createPostgreSqlCreateCommand(),
            $this->createPostgreSqlDropCommand(),
            $this->createPostgreSqlExportCommand(),
            $this->createPostgreSqlImportCommand(),
            $this->createPostgreSqlCleanCommand()
        );

        return $postgreSqlAdapter;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\CreatePostgreSqlDatabase
     */
    protected function createPostgreSqlCreateCommand()
    {
        return new CreatePostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\DropPostgreSqlDatabase
     */
    protected function createPostgreSqlDropCommand()
    {
        return new DropPostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\ExportPostgreSqlDatabase
     */
    protected function createPostgreSqlExportCommand()
    {
        return new ExportPostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\ImportPostgreSqlDatabase
     */
    protected function createPostgreSqlImportCommand()
    {
        return new ImportPostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CleanDatabaseInterface
     */
    protected function createPostgreSqlCleanCommand(): CleanDatabaseInterface
    {
        return new CleanPostgreSqlDatabase();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CleanDatabaseInterface
     */
    protected function createMySqlCleanCommand(): CleanDatabaseInterface
    {
        return new CleanMySqlDatabase();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface;

class Adapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $adapter;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface
     */
    protected $createCommand;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface
     */
    protected $dropCommand;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface
     */
    protected $exportCommand;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface
     */
    protected $importCommand;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface
     */
    protected $dropTablesCommand;

    /**
     * @param string $adapter
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface $createCommand
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface $dropCommand
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface $exportCommand
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface $importCommand
     * @param \Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface $dropTablesCommand
     */
    public function __construct(
        $adapter,
        CreateDatabaseInterface $createCommand,
        DropDatabaseInterface $dropCommand,
        ExportDatabaseInterface $exportCommand,
        ImportDatabaseInterface $importCommand,
        DropDatabaseTablesInterface $dropTablesCommand
    ) {
        $this->adapter = $adapter;
        $this->createCommand = $createCommand;
        $this->dropCommand = $dropCommand;
        $this->exportCommand = $exportCommand;
        $this->importCommand = $importCommand;
        $this->dropTablesCommand = $dropTablesCommand;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->adapter;
    }

    /**
     * @return void
     */
    public function createIfNotExists()
    {
        $this->createCommand->createIfNotExists();
    }

    /**
     * @return void
     */
    public function dropDatabase()
    {
        $this->dropCommand->dropDatabase();
    }

    /**
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath)
    {
        $this->exportCommand->exportDatabase($backupPath);
    }

    /**
     * @param string $backupPath
     *
     * @return void
     */
    public function importDatabase($backupPath)
    {
        $this->importCommand->importDatabase($backupPath);
    }

    /**
     * @return void
     */
    public function dropTables(): void
    {
        $this->dropTablesCommand->dropTables();
    }
}

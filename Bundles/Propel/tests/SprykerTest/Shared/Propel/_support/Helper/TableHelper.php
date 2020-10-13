<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\TableTransfer;
use Propel\Generator\Model\Column;
use Propel\Generator\Model\Database;
use Propel\Generator\Model\Domain;
use Propel\Generator\Model\Table;
use Propel\Generator\Platform\DefaultPlatform;
use Propel\Runtime\Propel;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;

class TableHelper extends Module
{
    /**
     * @return \Propel\Generator\Platform\DefaultPlatform
     */
    protected function findPlatform(): DefaultPlatform
    {
        $dbEngine = Config::get(PropelConstants::ZED_DB_ENGINE);
        $class = '\\Propel\\Generator\\Platform\\' . ucfirst(strtolower($dbEngine)) . 'Platform';
        if (!class_exists($class)) {
            return new DefaultPlatform();
        }

        return new $class();
    }

    /**
     * @param \Generated\Shared\Transfer\TableTransfer $tableTransfer
     *
     * @return \Propel\Generator\Model\Table
     */
    public function createTable(TableTransfer $tableTransfer): Table
    {
        $platform = $this->findPlatform();
        $connection = Propel::getConnection();

        $database = new Database(Config::get(PropelConstants::ZED_DB_DATABASE), $platform);
        $database->setPlatform($platform);

        $table = new Table($tableTransfer->getName());
        $table->setNamespace($tableTransfer->getNamespace());
        $database->addTable($table);

        foreach ($tableTransfer->getColumns() as $columnTransfer) {
            $columnType = $columnTransfer->getType();
            $column = new Column($columnTransfer->getName(), $columnType);

            $domain = new Domain($columnType, $columnType);
            $column->setDomain($domain);

            $table->addColumn($column);
        }

        $createTableSql = $platform->getAddTableDDL($table);
        $statement = $connection->prepare($createTableSql);
        $statement->execute();

        return $table;
    }

    /**
     * @param \Propel\Generator\Model\Table $table
     *
     * @return void
     */
    public function dropTable(Table $table): void
    {
        $platform = $this->findPlatform();
        $connection = Propel::getConnection();

        $dropTableSql = $platform->getDropTableDDL($table);
        $statement = $connection->prepare($dropTableSql);

        $statement->execute();
    }
}

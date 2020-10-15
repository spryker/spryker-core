<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Module;
use Codeception\TestInterface;
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
     * @var \Propel\Generator\Model\Table[]
     */
    protected $tables = [];

    /**
     * @param string $name
     * @param array $columnsData
     * @param string $namespace
     *
     * @return \Propel\Generator\Model\Table
     */
    public function createTable(string $name, array $columnsData, string $namespace = ''): Table
    {
        $platform = $this->getPlatform();
        $connection = Propel::getConnection();

        $database = new Database(Config::get(PropelConstants::ZED_DB_DATABASE), $platform);
        $database->setPlatform($platform);

        $table = new Table($name);
        $table->setNamespace($namespace);
        $database->addTable($table);

        foreach ($columnsData as $columnData) {
            $column = new Column($columnData['name'], $columnData['type']);
            $column->setDomain(new Domain($columnData['type'], $columnData['type']));

            $table->addColumn($column);
        }

        $createTableSql = $platform->getAddTableDDL($table);
        $statement = $connection->prepare($createTableSql);
        $statement->execute();

        $this->tables[] = $table;

        return $table;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        parent::_after($test);

        $this->dropTables();
    }

    /**
     * @return \Propel\Generator\Platform\DefaultPlatform
     */
    protected function getPlatform(): DefaultPlatform
    {
        $dbEngine = Config::get(PropelConstants::ZED_DB_ENGINE);
        $class = sprintf(
            '\\Propel\\Generator\\Platform\\%sPlatform',
            ucfirst(strtolower($dbEngine))
        );
        if (!class_exists($class)) {
            return new DefaultPlatform();
        }

        return new $class();
    }

    /**
     * @return void
     */
    protected function dropTables(): void
    {
        $platform = $this->getPlatform();
        $connection = Propel::getConnection();

        foreach ($this->tables as $table) {
            $dropTableSql = $platform->getDropTableDDL($table);
            $statement = $connection->prepare($dropTableSql);

            $statement->execute();
        }
    }
}

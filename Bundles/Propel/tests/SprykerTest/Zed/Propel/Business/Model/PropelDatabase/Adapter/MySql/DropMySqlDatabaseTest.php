<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase;

use Codeception\Test\Unit;
use PDO;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\DropMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\MySqlDatabaseCreator;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelDatabase
 * @group Adapter
 * @group MySql
 * @group DropMySqlDatabaseTests
 * Add your own group annotations below this line
 */
class DropMySqlDatabaseTests extends Unit
{
    /**
     * @return void
     */
    public function testCreateIfNotExists(): void
    {
        $mySqlDatabaseCreatorMock = $this->getMySqlDatabaseCreatorMock();
        $pdo = new PDO('sqlite::memory:');
        $mySqlDatabaseCreatorMock->expects($this->once())->method('getConnection')->willReturn($pdo);

        $dbName = Config::get(PropelConstants::ZED_DB_DATABASE);
        $pdo->exec('CREATE DATABASE IF NOT EXISTS ' . $dbName . ' CHARACTER SET "utf8"');
        var_dump($pdo->exec("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = $dbName")); exit;

        $mySqlDatabaseCreatorMock->dropDatabase();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\DropMySqlDatabase
     */
    protected function getMySqlDatabaseCreatorMock(): DropMySqlDatabase
    {
        return $this->getMockBuilder(DropMySqlDatabase::class)
            ->setMethods(['getConnection'])
            ->getMock();
    }
}

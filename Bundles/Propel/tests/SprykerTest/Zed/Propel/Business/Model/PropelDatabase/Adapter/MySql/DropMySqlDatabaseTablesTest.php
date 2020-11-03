<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql;

use Codeception\Test\Unit;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\DropMySqlDatabaseTables;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface;
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
 * @group DropMySqlDatabaseTablesTest
 * Add your own group annotations below this line
 */
class DropMySqlDatabaseTablesTest extends Unit
{
    /**
     * @return void
     */
    public function testDropTablesShouldDropTables(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_MYSQL) {
            $this->markTestSkipped('MySQL related test');
        }

        // Arrange
        $dropMySqlDatabaseTablesMock = $this->getDropMySqlDatabaseTablesMock();
        $connectionMock = $this->getConnectionMock();

        $dropMySqlDatabaseTablesMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);
        $dropMySqlDatabaseTablesMock->expects($this->once())
            ->method('getDropQuery');
        $connectionMock->expects($this->once())
            ->method('exec');

        // Act
        $dropMySqlDatabaseTablesMock->dropTables();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface
     */
    protected function getDropMySqlDatabaseTablesMock(): DropDatabaseTablesInterface
    {
        return $this->getMockBuilder(DropMySqlDatabaseTables::class)
            ->onlyMethods(['getConnection', 'getDropQuery'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getConnectionMock(): ConnectionInterface
    {
        return $this->getMockForAbstractClass(ConnectionInterface::class);
    }
}

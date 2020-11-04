<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use Codeception\Test\Unit;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\DropPostgreSqlDatabaseTables;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface;

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
 * @group PostgreSql
 * @group DropPostgreSqlDatabaseTablesTest
 * Add your own group annotations below this line
 */
class DropPostgreSqlDatabaseTablesTest extends Unit
{
    /**
     * @return void
     */
    public function testDropTablesShouldDropTables(): void
    {
        $dropPostgreSqlDatabaseTablesMock = $this->getDropPostgreSqlDatabaseTablesMock();
        $connectionMock = $this->getConnectionMock();

        $dropPostgreSqlDatabaseTablesMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);
        $dropPostgreSqlDatabaseTablesMock->expects($this->once())
            ->method('getDropQuery');

        $connectionMock->expects($this->once())
            ->method('exec');

        $dropPostgreSqlDatabaseTablesMock->dropTables();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface
     */
    protected function getDropPostgreSqlDatabaseTablesMock(): DropDatabaseTablesInterface
    {
        return $this->getMockBuilder(DropPostgreSqlDatabaseTables::class)
            ->disableOriginalConstructor()
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

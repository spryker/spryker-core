<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql;

use Codeception\Test\Unit;
use PDO;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\DropMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface;
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
 * @group DropMySqlDatabaseTest
 * Add your own group annotations below this line
 */
class DropMySqlDatabaseTest extends Unit
{
    /**
     * @return void
     */
    public function testDropDatabaseShouldDropDatabase(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_MYSQL) {
            $this->markTestSkipped('MySQL related test');
        }

        // Arrange
        $dropMySqlDatabaseMock = $this->getDropMySqlDatabaseMock();
        $pdoMock = $this->getPdoMock();

        $dropMySqlDatabaseMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdoMock);
        $dropMySqlDatabaseMock->expects($this->once())
            ->method('getQuery');
        $pdoMock->expects($this->once())
            ->method('exec');

        // Act
        $dropMySqlDatabaseMock->dropDatabase();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface
     */
    protected function getDropMySqlDatabaseMock(): DropDatabaseInterface
    {
        return $this->getMockBuilder(DropMySqlDatabase::class)
            ->onlyMethods(['getConnection', 'getQuery'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\PDO
     */
    protected function getPdoMock(): PDO
    {
        return $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['exec'])
            ->getMock();
    }
}

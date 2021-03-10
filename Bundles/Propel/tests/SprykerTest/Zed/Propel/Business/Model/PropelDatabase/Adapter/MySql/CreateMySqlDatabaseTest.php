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
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql\CreateMySqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface;
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
 * @group CreateMySqlDatabaseTest
 * Add your own group annotations below this line
 */
class CreateMySqlDatabaseTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateIfNotExistsShouldCreateDatabase(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_MYSQL) {
            $this->markTestSkipped('MySQL related test');
        }

        // Arrange
        $createMySqlDatabaseMock = $this->getCreateMySqlDatabaseMock();
        $pdoMock = $this->getPdoMock();

        $createMySqlDatabaseMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdoMock);
        $createMySqlDatabaseMock->expects($this->once())
            ->method('getQuery')
            ->willReturn('');
        $pdoMock->expects($this->once())
            ->method('exec');

        // Act
        $createMySqlDatabaseMock->createIfNotExists();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface
     */
    protected function getCreateMySqlDatabaseMock(): CreateDatabaseInterface
    {
        return $this->getMockBuilder(CreateMySqlDatabase::class)
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

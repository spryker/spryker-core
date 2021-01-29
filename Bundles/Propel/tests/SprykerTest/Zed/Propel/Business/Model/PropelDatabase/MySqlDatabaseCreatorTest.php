<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase;

use Codeception\Test\Unit;
use PDO;
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
 * @group MySqlDatabaseCreatorTest
 * Add your own group annotations below this line
 */
class MySqlDatabaseCreatorTest extends Unit
{
    /**
     * @return void
     */
    public function testGetEngine(): void
    {
        $mySqlDatabaseCreator = new MySqlDatabaseCreator();

        $this->assertSame(PropelConfig::DB_ENGINE_MYSQL, $mySqlDatabaseCreator->getEngine());
    }

    /**
     * @return void
     */
    public function testCreateIfNotExists(): void
    {
        $mySqlDatabaseCreatorMock = $this->getMySqlDatabaseCreatorMock();

        $mySqlDatabaseCreatorMock->createIfNotExists();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\MySqlDatabaseCreator
     */
    protected function getMySqlDatabaseCreatorMock(): MySqlDatabaseCreator
    {
        $mySqlDatabaseCreatorMock = $this->getMockBuilder(MySqlDatabaseCreator::class)
            ->onlyMethods(['getConnection', 'getQuery'])
            ->getMock();
        $pdoMock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['exec'])
            ->getMock();

        $mySqlDatabaseCreatorMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($pdoMock);

        $mySqlDatabaseCreatorMock->expects($this->once())
            ->method('getQuery');

        $pdoMock->expects($this->once())
            ->method('exec');

        return $mySqlDatabaseCreatorMock;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel\Business\Model\PropelDatabase;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\MySqlDatabaseCreator;
use Spryker\Zed\Propel\PropelConfig;

/**
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 */
class MySqlDatabaseCreatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetEngine()
    {
        $mySqlDatabaseCreator = new MySqlDatabaseCreator();

        $this->assertSame(PropelConfig::DB_ENGINE_MYSQL, $mySqlDatabaseCreator->getEngine());
    }

    /**
     * @return void
     */
    public function testCreateIfNotExists()
    {
        $mySqlDatabaseCreatorMock = $this->getMySqlDatabaseCreatorMock();

        $mySqlDatabaseCreatorMock->createIfNotExists();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\MySqlDatabaseCreator
     */
    protected function getMySqlDatabaseCreatorMock()
    {
        $mySqlDatabaseCreatorMock = $this->getMock(MySqlDatabaseCreator::class, ['getConnection']);
        $pdo = new \PDO('sqlite::memory:');
        $mySqlDatabaseCreatorMock->expects($this->once())->method('getConnection')->willReturn($pdo);

        return $mySqlDatabaseCreatorMock;
    }

}

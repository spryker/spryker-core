<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel\Business\Model\PropelDatabase;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator;
use Spryker\Zed\Propel\PropelConfig;

/**
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 */
class PostgreSqlDatabaseCreatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetEngine()
    {
        $postgreSqlDatabaseCreator = new PostgreSqlDatabaseCreator();

        $this->assertSame(PropelConfig::DB_ENGINE_PGSQL, $postgreSqlDatabaseCreator->getEngine());
    }

    /**
     * @return void
     */
    public function testCreateWithNotExistingDatabase()
    {
        $postgreSqlDatabaseCreatorMock = $this->getPostgreSqlDatabaseCreatorMock(['existsDatabase', 'createDatabase']);
        $postgreSqlDatabaseCreatorMock->expects($this->once())->method('existsDatabase')->willReturn(false);
        $postgreSqlDatabaseCreatorMock->expects($this->once())->method('createDatabase');

        $postgreSqlDatabaseCreatorMock->createIfNotExists();
    }

    /**
     * @return void
     */
    public function testCreateWithExistingDatabase()
    {
        $postgreSqlDatabaseCreatorMock = $this->getPostgreSqlDatabaseCreatorMock(['existsDatabase', 'createDatabase']);
        $postgreSqlDatabaseCreatorMock->expects($this->once())->method('existsDatabase')->willReturn(true);
        $postgreSqlDatabaseCreatorMock->expects($this->never())->method('createDatabase');

        $postgreSqlDatabaseCreatorMock->createIfNotExists();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator
     */
    protected function getPostgreSqlDatabaseCreatorMock(array $methods = [])
    {
        return $this->getMock(PostgreSqlDatabaseCreator::class, $methods);
    }

}

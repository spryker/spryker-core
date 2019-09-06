<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelDatabase
 * @group PostgreSqlDatabaseCreatorTest
 * Add your own group annotations below this line
 */
class PostgreSqlDatabaseCreatorTest extends Unit
{
    /**
     * @return void
     */
    public function testGetEngine()
    {
        $postgreSqlDatabaseCreator = new PostgreSqlDatabaseCreator(
            $this->getConfigMock()
        );

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator
     */
    protected function getPostgreSqlDatabaseCreatorMock(array $methods = [])
    {
        return $this->getMockBuilder(PostgreSqlDatabaseCreator::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\PropelConfig
     */
    protected function getConfigMock(): MockObject
    {
        $configMock = $this->createMock(PropelConfig::class);
        $configMock->method('getProcessTimeout')->willReturn(null);

        return $configMock;
    }
}

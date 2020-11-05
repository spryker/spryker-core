<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\DropPostgreSqlDatabase;

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
 * @group DropPostgreSqlDatabaseTest
 * Add your own group annotations below this line
 */
class DropPostgreSqlDatabaseTest extends Unit
{
    /**
     * @return void
     */
    public function testDropDatabase(): void
    {
        $dropPostgreSqlDatabaseMock = $this->getDropPostgreSqlDatabaseMock([
            'closeOpenConnections',
            'runDropCommand',
        ]);

        $dropPostgreSqlDatabaseMock->expects($this->once())->method('closeOpenConnections');
        $dropPostgreSqlDatabaseMock->expects($this->once())->method('runDropCommand');

        $dropPostgreSqlDatabaseMock->dropDatabase();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\DropPostgreSqlDatabase
     */
    protected function getDropPostgreSqlDatabaseMock(array $methods = []): DropPostgreSqlDatabase
    {
        return $this->getMockBuilder(DropPostgreSqlDatabase::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\DropPostgreSqlDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator;
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
 * @group PostgreSql
 * @group DropPostgreSqlDatabaseTest
 * Add your own group annotations below this line
 */
class DropPostgreSqlDatabaseTest extends Unit
{
    /**
     * @return void
     */
    public function testDropDatabaseWithSudo(): void
    {
        $postgreSqlDatabaseCreatorMock = new DropPostgreSqlDatabase($this->getConfigMock());
        $postgreSqlDatabaseCreatorMock->expects($this->once())->method('useSudo')->willReturn(false);
        $postgreSqlDatabaseCreatorMock->expects($this->never())->method('getSudoDropCommand');
        $postgreSqlDatabaseCreatorMock->expects($this->once())->method('getDropCommandRemote');

        $postgreSqlDatabaseCreatorMock->dropDatabase();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator
     */
    protected function getDropPostgreSqlDatabaseMock(array $methods = []): DropPostgreSqlDatabase
    {
        return $this->getMockBuilder(DropPostgreSqlDatabase::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\PropelConfig
     */
    protected function getConfigMock(): PropelConfig
    {
        $configMock = $this->createMock(PropelConfig::class);
        $configMock->method('getProcessTimeout')->willReturn(null);

        return $configMock;
    }
}

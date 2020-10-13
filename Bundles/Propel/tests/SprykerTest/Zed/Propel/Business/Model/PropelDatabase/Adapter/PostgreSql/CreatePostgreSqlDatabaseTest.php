<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\CreatePostgreSqlDatabase;

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
 * @group CreatePostgreSqlDatabaseTest
 * Add your own group annotations below this line
 */
class CreatePostgreSqlDatabaseTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateIfNotExistsShouldCreateDatabaseWhenDatabaseIsNotExist(): void
    {
        $createPostgreSqlDatabaseMock = $this->getCreatePostgreSqlDatabaseMock();
        $createPostgreSqlDatabaseMock->expects($this->once())
            ->method('existsDatabase')
            ->willReturn(false);
        $createPostgreSqlDatabaseMock->expects($this->once())
            ->method('createDatabase');

        $createPostgreSqlDatabaseMock->createIfNotExists();
    }

    /**
     * @return void
     */
    public function testCreateIfNotExistsShouldSkipDatabaseCreationWhenDatabaseIsExist(): void
    {
        $createPostgreSqlDatabaseMock = $this->getCreatePostgreSqlDatabaseMock();
        $createPostgreSqlDatabaseMock->expects($this->once())
            ->method('existsDatabase')
            ->willReturn(true);
        $createPostgreSqlDatabaseMock->expects($this->never())
            ->method('createDatabase');

        $createPostgreSqlDatabaseMock->createIfNotExists();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql\CreatePostgreSqlDatabase
     */
    protected function getCreatePostgreSqlDatabaseMock(): CreatePostgreSqlDatabase
    {
        return $this->getMockBuilder(CreatePostgreSqlDatabase::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['existsDatabase', 'createDatabase'])
            ->getMock();
    }
}

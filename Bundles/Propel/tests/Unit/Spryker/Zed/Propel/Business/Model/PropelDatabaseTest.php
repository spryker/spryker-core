<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Exception\DatabaseCreatorNotFoundException;
use Spryker\Zed\Propel\Business\Model\PropelDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollectionInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelDatabaseTest
 */
class PropelDatabaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInitialization()
    {
        $databaseCreatorCollectionMock = $this->getMockBuilder(DatabaseCreatorCollectionInterface::class)->getMock();

        $this->assertInstanceOf(PropelDatabase::class, new PropelDatabase($databaseCreatorCollectionMock));
    }

    /**
     * @return void
     */
    public function testCreateIfNotExists()
    {
        $databaseCreatorMock = $this->getMockBuilder(DatabaseCreatorInterface::class)->setMethods(['createIfNotExists', 'getEngine'])->getMock();
        $databaseCreatorMock->expects($this->once())->method('createIfNotExists');

        $databaseCreatorCollectionMock = $this->getMockBuilder(DatabaseCreatorCollectionInterface::class)->setMethods(['has', 'get', 'add'])->getMock();
        $databaseCreatorCollectionMock->expects($this->once())->method('has')->willReturn(true);
        $databaseCreatorCollectionMock->expects($this->once())->method('get')->willReturn($databaseCreatorMock);

        $propelDatabase = new PropelDatabase($databaseCreatorCollectionMock);
        $propelDatabase->createDatabaseIfNotExists();
    }

    /**
     * @return void
     */
    public function testCreateIfNotExistsThrowsException()
    {
        $this->expectException(DatabaseCreatorNotFoundException::class);

        $databaseCreatorCollectionMock = $this->getMockBuilder(DatabaseCreatorCollectionInterface::class)->getMock();
        $propelDatabase = new PropelDatabase($databaseCreatorCollectionMock);
        $propelDatabase->createDatabaseIfNotExists();
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model\PropelDatabase;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollection;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelDatabase
 * @group DatabaseCreatorCollectionTest
 * Add your own group annotations below this line
 */
class DatabaseCreatorCollectionTest extends Unit
{
    public const TEST_ENGINE = 'testEngine';

    /**
     * @return void
     */
    public function testAdd()
    {
        $databaseCreatorMock = $this->getDatabaseCreatorMock();
        $databaseCreatorCollection = new DatabaseCreatorCollection();

        $this->assertSame($databaseCreatorCollection, $databaseCreatorCollection->add($databaseCreatorMock));
    }

    /**
     * @return void
     */
    public function testHasReturnTrue()
    {
        $databaseCreatorMock = $this->getDatabaseCreatorMock();
        $databaseCreatorCollection = new DatabaseCreatorCollection();
        $databaseCreatorCollection->add($databaseCreatorMock);

        $this->assertTrue($databaseCreatorCollection->has(self::TEST_ENGINE));
    }

    /**
     * @return void
     */
    public function testHasReturnFalse()
    {
        $databaseCreatorMock = $this->getDatabaseCreatorMock();
        $databaseCreatorCollection = new DatabaseCreatorCollection();
        $databaseCreatorCollection->add($databaseCreatorMock);

        $this->assertFalse($databaseCreatorCollection->has('no existing engine'));
    }

    /**
     * @return void
     */
    public function testGet()
    {
        $databaseCreatorMock = $this->getDatabaseCreatorMock();
        $databaseCreatorCollection = new DatabaseCreatorCollection();
        $databaseCreatorCollection->add($databaseCreatorMock);

        $this->assertSame($databaseCreatorMock, $databaseCreatorCollection->get(self::TEST_ENGINE));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface
     */
    private function getDatabaseCreatorMock()
    {
        $databaseCreatorMock = $this->getMockBuilder(DatabaseCreatorInterface::class)->setMethods(['getEngine', 'createIfNotExists'])->getMock();
        $databaseCreatorMock->expects($this->once())->method('getEngine')->willReturn(self::TEST_ENGINE);

        return $databaseCreatorMock;
    }
}

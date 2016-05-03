<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel\Business\Model\PropelDatabase;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollection;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 */
class DatabaseCreatorCollectionTest extends \PHPUnit_Framework_TestCase
{

    const TEST_ENGINE = 'testEngine';

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
        $databaseCreatorMock = $this->getMock(DatabaseCreatorInterface::class, ['getEngine', 'createIfNotExists']);
        $databaseCreatorMock->expects($this->once())->method('getEngine')->willReturn(self::TEST_ENGINE);

        return $databaseCreatorMock;
    }

}

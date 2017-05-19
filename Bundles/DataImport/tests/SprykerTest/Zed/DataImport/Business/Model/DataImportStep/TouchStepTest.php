<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\TestCase\Test;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataImportStep
 * @group TouchStepTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class TouchStepTest extends Test
{

    const ITEM_TYPE_KEY = 'itemTypeKey';
    const ITEM_ID_KEY = 'itemIdKey';

    /**
     * @return void
     */
    public function testIsNotExecutedWhenDataSetDoesNotContainTheConfiguredItemTypeKeyOrTheItemIdKey()
    {
        $this->tester->setDependency(DataImportDependencyProvider::FACADE_TOUCH, $this->getTouchFacadeMock(false, false));
        $touchStep = $this->tester->getFactory()->createTouchStep(static::ITEM_TYPE_KEY, static::ITEM_ID_KEY);
        $dataSet = $this->tester->getFactory()->createDataSet();

        $touchStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTouchIsExecutedAfterEachStepWhenBulkSizeNotSet()
    {
        $this->tester->setDependency(DataImportDependencyProvider::FACADE_TOUCH, $this->getTouchFacadeMock());
        $touchStep = $this->tester->getFactory()->createTouchStep(static::ITEM_TYPE_KEY, static::ITEM_ID_KEY);
        $dataSet = $this->tester->getFactory()->createDataSet([static::ITEM_TYPE_KEY => 'item type', static::ITEM_ID_KEY => 'item id']);

        $touchStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTouchIsExecutedAfterEachStepWhenBulkSizeSetToOne()
    {
        $this->tester->setDependency(DataImportDependencyProvider::FACADE_TOUCH, $this->getTouchFacadeMock());
        $touchStep = $this->tester->getFactory()->createTouchStep(static::ITEM_TYPE_KEY, static::ITEM_ID_KEY, 1);
        $dataSet = $this->tester->getFactory()->createDataSet([static::ITEM_TYPE_KEY => 'item type', static::ITEM_ID_KEY => 'item id']);

        $touchStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testBulkTouchIsExecutedAfterConfiguredBulkSize()
    {
        $this->tester->setDependency(DataImportDependencyProvider::FACADE_TOUCH, $this->getTouchFacadeMock(false, true));
        $touchStep = $this->tester->getFactory()->createTouchStep(static::ITEM_TYPE_KEY, static::ITEM_ID_KEY, 2);
        $dataSet = $this->tester->getFactory()->createDataSet([static::ITEM_TYPE_KEY => 'item type', static::ITEM_ID_KEY => 'item id']);

        $touchStep->execute($dataSet);
        $touchStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testBulkTouchIsExecutedWhenExecutedNumberOfItemTypesLowerThenBulkSizeWhenClassDestructed()
    {
        $this->tester->setDependency(DataImportDependencyProvider::FACADE_TOUCH, $this->getTouchFacadeMock(false, true));
        $touchStep = $this->tester->getFactory()->createTouchStep(static::ITEM_TYPE_KEY, static::ITEM_ID_KEY, 2);
        $dataSet = $this->tester->getFactory()->createDataSet([static::ITEM_TYPE_KEY => 'item type', static::ITEM_ID_KEY => 'item id']);

        $touchStep->execute($dataSet);
    }

    /**
     * @param bool $isTouchCalledCalled
     * @param bool $isBulkTouchCalled
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    private function getTouchFacadeMock($isTouchCalledCalled = true, $isBulkTouchCalled = false)
    {
        $mockBuilder = $this->getMockBuilder(DataImportToTouchInterface::class)
            ->setMethods(['touchActive', 'bulkTouchSetActive']);

        $dataImportToTouchInterfaceMock = $mockBuilder->getMock();
        $dataImportToTouchInterfaceMock->expects(($isTouchCalledCalled) ? $this->once() : $this->never())->method('touchActive');
        $dataImportToTouchInterfaceMock->expects(($isBulkTouchCalled) ? $this->once() : $this->never())->method('bulkTouchSetActive');

        return $dataImportToTouchInterfaceMock;
    }

}

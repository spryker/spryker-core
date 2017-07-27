<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\Test\Unit;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\TouchAwareStep;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataImportStep
 * @group TouchAwareStepTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class TouchAwareStepTest extends Unit
{

    const MAIN_TOUCHABLE_KEY = 'main touchable key';
    const SUB_TOUCHABLE_KEY_A = 'sub touchable key a';
    const SUB_TOUCHABLE_KEY_B = 'sub touchable key b';

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForMainTouchableWhenBulkSizeNotSet()
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock());
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForMainTouchableWhenBulkSizeOne()
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(), 1);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchWhenMainTouchableCountEqualsBulkSize()
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(), 2);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 2);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForSubTouchableWhenMainTouchableCountEqualsBulkSize()
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(2), 2);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 2);

        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 2);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 3);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 4);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForEachSubTouchableWhenMainTouchableCountEqualsBulkSize()
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(3), 2);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_B, 2);

        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 2);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 2);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_B, 2);

        $touchAwareStep->afterExecute();
    }

    /**
     * @param int $calledCount
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    private function getTouchFacadeMock($calledCount = 1)
    {
        $mockBuilder = $this->getMockBuilder(DataImportToTouchInterface::class)
            ->setMethods(['bulkTouchSetActive']);

        $dataImportToTouchInterfaceMock = $mockBuilder->getMock();
        $dataImportToTouchInterfaceMock->expects($this->exactly($calledCount))->method('bulkTouchSetActive');

        $dataImportToTouchBridge = new DataImportToTouchBridge($dataImportToTouchInterfaceMock);

        return $dataImportToTouchBridge;
    }

}

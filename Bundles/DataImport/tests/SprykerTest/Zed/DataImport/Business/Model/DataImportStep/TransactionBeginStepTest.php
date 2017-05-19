<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\TestCase\Test;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataImportStep
 * @group TransactionBeginStepTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class TransactionBeginStepTest extends Test
{

    /**
     * @return void
     */
    public function testExecutesIsOpenedTransactionOnFirstCall()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(false, 1);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionBeginStep();
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionOnlyOpenedOnceForConfiguredBulkSize()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(false, 1);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionBeginStep(2);
        $transactionBeginStep->execute($dataSet);
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionIsOpenedForeachConfiguredBulkSize()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(false, 2);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionBeginStep(2);
        $transactionBeginStep->execute($dataSet);
        $transactionBeginStep->execute($dataSet);

        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionNotOpenedWhenThereIsAlreadyInTransaction()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(true, 0);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionBeginStep();
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @param bool $isInTransaction
     * @param int $beginTransactionCalledCount
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    private function getPropelConnectionMock($isInTransaction, $beginTransactionCalledCount = 0)
    {
        $mockBuilder = $this->getMockBuilder(DataImportToPropelConnectionInterface::class)
            ->setMethods(['inTransaction', 'beginTransaction', 'endTransaction']);

        $propelConnectionMock = $mockBuilder->getMock();
        $propelConnectionMock->method('inTransaction')->willReturn($isInTransaction);
        $propelConnectionMock->expects($this->exactly($beginTransactionCalledCount))->method('beginTransaction');

        return $propelConnectionMock;
    }

}

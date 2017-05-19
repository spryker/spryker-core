<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\TestCase\Test;
use Spryker\Zed\DataImport\Business\Exception\TransactionException;
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
 * @group TransactionEndStepTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class TransactionEndStepTest extends Test
{

    /**
     * @return void
     */
    public function testTransactionIsClosedForeachConfiguredBulkSize()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(1);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionEndStep();
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionIsOnlyClosedOnceForConfiguredBulkSize()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(1);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionEndStep(2);
        $transactionBeginStep->execute($dataSet);
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionIsAlwaysClosedWhenThereIsAnOpenedOne()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(2, true);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionEndStep(2);

        $transactionBeginStep->execute($dataSet);
        $transactionBeginStep->execute($dataSet);
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfNoOpenTransactionGiven()
    {
        $propelConnectionMock = $this->getPropelConnectionMock(0, false);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->tester->getFactory()->createDataSet();
        $transactionBeginStep = $this->tester->getFactory()->createTransactionEndStep();

        $this->expectException(TransactionException::class);
        $transactionBeginStep->execute($dataSet);
    }

    /**
     * @param int $endTransactionCalledCount
     * @param bool $inTransaction
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    private function getPropelConnectionMock($endTransactionCalledCount = 0, $inTransaction = true)
    {
        $mockBuilder = $this->getMockBuilder(DataImportToPropelConnectionInterface::class)
            ->setMethods(['inTransaction', 'beginTransaction', 'endTransaction']);

        $propelConnectionMock = $mockBuilder->getMock();
        $propelConnectionMock->expects($this->exactly($endTransactionCalledCount))->method('endTransaction');
        $propelConnectionMock->method('inTransaction')->willReturn($inTransaction);

        return $propelConnectionMock;
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataSet;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataSetBrokerTransactionFailedException;
use Spryker\Zed\DataImport\Business\Exception\TransactionException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerElasticBatchTransactionAware;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataSet
 * @group DataSetStepBrokerElasticBatchTransactionAwareTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\DataImportBusinessTester $tester
 */
class DataSetStepBrokerElasticBatchTransactionAwareTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteOpensTransactionOnFirstCall(): void
    {
        //Arrange
        $propelConnectionMock = $this->getPropelConnectionMock(1, 1, false, true);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->createDataSet();
        $elasticBatchDataSetStepBrokerTransactionAware = $this->createElasticBatchStepBroker();

        //Act
        $elasticBatchDataSetStepBrokerTransactionAware->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionNotOpenedWhenAlreadyInTransaction(): void
    {
        //Arrange
        $propelConnectionMock = $this->getPropelConnectionMock(0, 1, true, true);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->createDataSet();
        $elasticBatchDataSetStepBrokerTransactionAware = $this->createElasticBatchStepBroker();

        //Act
        $elasticBatchDataSetStepBrokerTransactionAware->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfNoOpenTransactionGiven(): void
    {
        //Arrange
        $propelConnectionMock = $this->getPropelConnectionMock(1, 0, false);
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataSet = $this->createDataSet();
        $elasticBatchDataSetStepBrokerTransactionAware = $this->createElasticBatchStepBroker();

        //Act
        $this->expectException(TransactionException::class);
        $elasticBatchDataSetStepBrokerTransactionAware->execute($dataSet);
    }

    /**
     * @return void
     */
    public function testTransactionRollBackOnWriterException(): void
    {
        //Arrange
        $propelConnectionMock = $this->createPropelConnectionMockWithExpectedRollBack();
        $this->tester->setDependency(DataImportDependencyProvider::PROPEL_CONNECTION, $propelConnectionMock);

        $dataImportStepMock = $this->createDataImportStepMockWithExpectedExceptionOnExecute();
        $dataSet = $this->createDataSet();
        $elasticBatchDataSetStepBrokerTransactionAware = $this->createElasticBatchStepBroker();
        $elasticBatchDataSetStepBrokerTransactionAware->addStep($dataImportStepMock);

        //Act
        $elasticBatchDataSetStepBrokerTransactionAware->execute($dataSet);
    }

    /**
     * @param int $beginTransactionCalledCount
     * @param int $endTransactionCalledCount
     * @param mixed $isInTransaction
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    private function getPropelConnectionMock(
        int $beginTransactionCalledCount,
        int $endTransactionCalledCount,
        ...$isInTransaction
    ): DataImportToPropelConnectionInterface {
        $mockBuilder = $this->getMockBuilder(DataImportToPropelConnectionInterface::class)
            ->setMethods(['inTransaction', 'beginTransaction', 'endTransaction', 'rollBack']);

        $propelConnectionMock = $mockBuilder->getMock();

        $propelConnectionMock->method('inTransaction')->will($this->onConsecutiveCalls(...$isInTransaction));
        $propelConnectionMock->expects($this->exactly($beginTransactionCalledCount))->method('beginTransaction');
        $propelConnectionMock->expects($this->exactly($endTransactionCalledCount))->method('endTransaction');

        return $propelConnectionMock;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function createDataSet()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');

        return $csvReader->current();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerElasticBatchTransactionAware
     */
    protected function createElasticBatchStepBroker(): DataSetStepBrokerElasticBatchTransactionAware
    {
        return $this->tester->getFactory()->createElasticBatchTransactionAwareDataSetStepBroker();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createDataImportStepMockWithExpectedExceptionOnExecute(): DataImportStepInterface
    {
        $dataImportStepMockBuilder = $this->getMockBuilder(DataImportStepInterface::class)
            ->setMethods(['execute']);
        $dataImportStepMock = $dataImportStepMockBuilder->getMock();
        $dataImportStepMock->expects($this->once())->method('execute')->willThrowException(new DataSetBrokerTransactionFailedException(10));
        $this->expectException(DataSetBrokerTransactionFailedException::class);

        return $dataImportStepMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    protected function createPropelConnectionMockWithExpectedRollBack(): DataImportToPropelConnectionInterface
    {
        $propelConnectionMock = $this->getPropelConnectionMock(1, 0, false);
        $propelConnectionMock->expects($this->exactly(1))->method('rollBack');

        return $propelConnectionMock;
    }

    /**
     * @param string $fileName
     * @param bool $hasHeader
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface|\Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface
     */
    protected function getCsvReader(string $fileName, bool $hasHeader = true, ?int $offset = null, ?int $limit = null)
    {
        $configuration = $this->getCsvReaderConfigurationTransfer($fileName, $hasHeader, $offset, $limit);
        $csvReader = $this->tester->getFactory()->createCsvReaderFromConfig($configuration);

        return $csvReader;
    }

    /**
     * @param string $fileName
     * @param bool $hasHeader
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer
     */
    protected function getCsvReaderConfigurationTransfer(
        string $fileName,
        bool $hasHeader = true,
        ?int $offset = null,
        ?int $limit = null
    ): DataImporterReaderConfigurationTransfer {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration
            ->setFileName($fileName)
            ->setCsvHasHeader($hasHeader)
            ->setOffset($offset)
            ->setLimit($limit);

        return $dataImporterReaderConfiguration;
    }
}

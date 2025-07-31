<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\DataReader\CsvReader;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Countable;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataReaderException;
use Spryker\Zed\DataImport\Business\Exception\DataSetWithHeaderCombineFailedException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface;
use SprykerTest\Zed\DataImport\DataImportBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataReader
 * @group CsvReader
 * @group CsvAdapterReaderTest
 * Add your own group annotations below this line
 */
class CsvAdapterReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected DataImportBusinessTester $tester;

    /**
     * @return void
     */
    public function testDataReaderCanBeUsedAsIteratorAndReturnsArrayObject(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        foreach ($csvReader as $dataSet) {
            $this->assertInstanceOf(DataSet::class, $dataSet);
        }
    }

    /**
     * @return void
     */
    public function testReaderIsCountable(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertInstanceOf(Countable::class, $csvReader);
    }

    /**
     * @return void
     */
    public function testKeyReturnsCurrentDataSetPosition(): void
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertIsInt($csvReader->key());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenFileInvalid(): void
    {
        $this->expectException(DataReaderException::class);
        $configuration = $this->getCsvReaderConfigurationTransfer(Configuration::dataDir() . 'not-existing.csv');

        $this->tester->getFactory()->createCsvReaderFromConfig($configuration);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenHeaderAndDataSetLengthDoesNotMatch(): void
    {
        $this->expectException(DataSetWithHeaderCombineFailedException::class);
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-header-dataset-length-missmatch.csv');

        $csvReader->current();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\DataImportConfig
     */
    protected function getConfigMock(): DataImportConfig
    {
        $configMock = $this->getMockBuilder(DataImportConfig::class)
            ->onlyMethods(['isDataImportFromOtherSourceEnabled'])
            ->getMock();

        $configMock->method('isDataImportFromOtherSourceEnabled')
            ->willReturn(true);

        return $configMock;
    }

    /**
     * @param string $fileName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface
     */
    protected function getFlysystemMock(string $fileName): DataImportToFlysystemServiceInterface
    {
        $flysystemServiceMock = $this->getMockBuilder(DataImportToFlysystemServiceInterface::class)->getMock();
        $flysystemServiceMock->expects($this->any())
            ->method('has')
            ->willReturn(true);
        $flysystemServiceMock->expects($this->any())
            ->method('readStream')
            ->willReturn(fopen($fileName, 'rb'));

        return $flysystemServiceMock;
    }

    /**
     * @param string $fileName
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface|\Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface
     */
    protected function getCsvReader(string $fileName)
    {
        $configuration = $this->getCsvReaderConfigurationTransfer($fileName);

        $this->tester->setDependency(DataImportDependencyProvider::SERVICE_FLYSYSTEM, $this->getFlysystemMock($fileName));
        $dataImportBusinessFactory = $this->tester->getFactory()->setConfig($this->getConfigMock());

        return $dataImportBusinessFactory->createCsvReaderFromConfig($configuration);
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer
     */
    protected function getCsvReaderConfigurationTransfer(
        string $fileName
    ): DataImporterReaderConfigurationTransfer {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration->setFileName($fileName);

        return $dataImporterReaderConfiguration;
    }
}

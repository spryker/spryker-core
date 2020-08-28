<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\DataImportFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Facade
 * @group DataImportFacadeTest
 * Add your own group annotations below this line
 */
class DataImportFacadeTest extends Unit
{
    public const IMPORT_TYPE_FULL_IMPORT = 'full';
    public const IMPORT_GROUP_FULL = 'FULL';
    public const IMPORT_TYPE_SPECIFIC_A = 'specific-importer-a';
    public const IMPORT_TYPE_SPECIFIC_B = 'specific-importer-b';

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportReturnsReport(): void
    {
        $dataImportFacade = $this->getFacade();
        $dataImporterReportTransfer = $dataImportFacade->import();

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return void
     */
    public function testImportReturnsReportWithFullImportAndNumberOfImportedDataSets(): void
    {
        $dataImportFacade = $this->getFacade();
        $dataImporterReportTransfer = $dataImportFacade->import();

        $this->assertSame(static::IMPORT_TYPE_FULL_IMPORT, $dataImporterReportTransfer->getImportType());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount(), 'Expected that number of imported data sets is 0');
    }

    /**
     * @return void
     */
    public function testImportReturnsReportWithSpecifiedImportType(): void
    {
        $dataImportFacade = $this->getFacade();
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer->setImportType(static::IMPORT_TYPE_SPECIFIC_A);
        $dataImporterReportTransfer = $dataImportFacade->import();

        $this->assertSame(static::IMPORT_TYPE_FULL_IMPORT, $dataImporterReportTransfer->getImportType());
    }

    /**
     * @return void
     */
    public function testImportExecutesFullImport(): void
    {
        $dataImportBusinessFactoryMock = $this->createDataImportBusinessFactoryMock();
        $dataImportFacade = $this->getFacade();
        $dataImportFacade->setFactory($dataImportBusinessFactoryMock);

        $dataImportFacade->import();
    }

    /**
     * @return void
     */
    public function testImportExecutesSpecificDataImporter(): void
    {
        $dataImportBusinessFactoryMock = $this->createDataImportBusinessFactoryMock();
        $dataImportFacade = $this->getFacade();
        $dataImportFacade->setFactory($dataImportBusinessFactoryMock);
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer->setImportType(static::IMPORT_TYPE_SPECIFIC_A);
        $dataImporterConfigurationTransfer->setImportGroup(static::IMPORT_GROUP_FULL);

        $dataImportFacade->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\DataImportFacade
     */
    private function getFacade(): DataImportFacade
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Business\DataImportBusinessFactory
     */
    private function createDataImportBusinessFactoryMock(): DataImportBusinessFactory
    {
        $mockBuilder = $this->getMockBuilder(DataImportBusinessFactory::class)
            ->setMethods(['createDataImporterCollection']);

        $dataImporterCollection = $this->tester->getFactory()->createDataImporterCollection();
        $dataImporterCollection
            ->addDataImporter($this->tester->getDataImporterMock(static::IMPORT_TYPE_SPECIFIC_A, true))
            ->addDataImporter($this->tester->getDataImporterMock(static::IMPORT_TYPE_SPECIFIC_B, true));

        $dataImportBusinessFactoryMock = $mockBuilder->getMock();
        $dataImportBusinessFactoryMock->method('createDataImporterCollection')->willReturn($dataImporterCollection);

        return $dataImportBusinessFactoryMock;
    }

    /**
     * @return void
     */
    public function testDumpImporterDumpsAListOfAppliedImporter(): void
    {
        $dumpedImporter = $this->getFacade()->listImporters();
        $this->assertIsArray($dumpedImporter);
    }
}

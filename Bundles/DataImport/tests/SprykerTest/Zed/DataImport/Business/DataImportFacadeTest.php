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

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Facade
 * @group DataImportFacadeTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataImportFacadeTest extends Unit
{
    const IMPORT_TYPE_FULL_IMPORT = 'full';
    const IMPORT_TYPE_SPECIFIC_A = 'specific-importer-a';
    const IMPORT_TYPE_SPECIFIC_B = 'specific-importer-b';

    /**
     * @return void
     */
    public function testImportReturnsReport()
    {
        $dataImportFacade = $this->getFacade();
        $dataImporterReportTransfer = $dataImportFacade->import();

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return void
     */
    public function testImportReturnsReportWithFullImportAndNumberOfImportedDataSets()
    {
        $dataImportFacade = $this->getFacade();
        $dataImporterReportTransfer = $dataImportFacade->import();

        $this->assertSame(static::IMPORT_TYPE_FULL_IMPORT, $dataImporterReportTransfer->getImportType());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount(), 'Expected that number of imported data sets is 0');
    }

    /**
     * @return void
     */
    public function testImportReturnsReportWithSpecifiedImportType()
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
    public function testImportExecutesFullImport()
    {
        $dataImportBusinessFactoryMock = $this->createDataImportBusinessFactoryMock();
        $dataImportFacade = $this->getFacade();
        $dataImportFacade->setFactory($dataImportBusinessFactoryMock);

        $dataImportFacade->import();
    }

    /**
     * @return void
     */
    public function testImportExecutesSpecificDataImporter()
    {
        $dataImportBusinessFactoryMock = $this->createDataImportBusinessFactoryMock();
        $dataImportFacade = $this->getFacade();
        $dataImportFacade->setFactory($dataImportBusinessFactoryMock);
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer->setImportType(static::IMPORT_TYPE_SPECIFIC_A);

        $dataImportFacade->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\DataImportFacade
     */
    private function getFacade()
    {
        return $this->tester->getLocator()->dataImport()->facade();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DataImport\Business\DataImportBusinessFactory
     */
    private function createDataImportBusinessFactoryMock()
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
    public function testDumpImporterDumpsAListOfAppliedImporter()
    {
        $dumpedImporter = $this->getFacade()->listImporters();
        $this->assertInternalType('array', $dumpedImporter);
    }
}

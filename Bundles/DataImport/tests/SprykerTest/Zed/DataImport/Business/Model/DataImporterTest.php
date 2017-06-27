<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Model
 * @group DataImporterTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataImporterTest extends Test
{

    const IMPORTER_TYPE = 'specific-importer';

    /**
     * @var \SprykerTest\Zed\DataImport\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetImporterTypeReturnsString()
    {
        $dataImporter = $this->getDataImporter();

        $this->assertSame(static::IMPORTER_TYPE, $dataImporter->getImportType());
    }

    /**
     * @return void
     */
    public function testImportReturnsReport()
    {
        $dataImporter = $this->getDataImporter();

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporter->import());
    }

    /**
     * @return void
     */
    public function testImportWithConfigurableDataReaderShouldReConfigureDataReader()
    {
        $dataImporter = $this->getDataImporter();

        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterConfigurationTransfer->setReaderConfiguration($dataImportReaderConfigurationTransfer);

        $dataImporter->import($dataImporterConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportExecutesDataSets()
    {
        $dataImporter = $this->getDataImporter();
        $dataImporter->addDataSetStepBroker($this->tester->getDataSetMock());

        $dataImporter->import();
    }

    /**
     * @return void
     */
    public function testImportExecutesBeforeImportHooks()
    {
        $dataImporter = $this->getDataImporter();
        $dataImporter->addBeforeImportHook($this->tester->getBeforeImportHookMock());

        $dataImporter->import();
    }

    /**
     * @return void
     */
    public function testImportExecutesAfterImportHooks()
    {
        $dataImporter = $this->getDataImporter();
        $dataImporter->addAfterImportHook($this->tester->getAfterImportHookMock());

        $dataImporter->import();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    private function getDataImporter()
    {
        $dataReader = $this->tester->getDataReader();
        $dataImporter = $this->tester->getFactory()->createDataImporter(static::IMPORTER_TYPE, $dataReader);

        return $dataImporter;
    }

}

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
use Spryker\Zed\DataImport\Business\Model\DataImporter;

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
        $dataImporter->addDataSetImporter($this->tester->getDataSetMock());

        $dataImporter->import();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporter
     */
    private function getDataImporter()
    {
        $dataReader = $this->tester->getDataReader();
        $dataImporter = new DataImporter(static::IMPORTER_TYPE, $dataReader);

        return $dataImporter;
    }

}

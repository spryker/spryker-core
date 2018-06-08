<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataImportBusinessFactoryTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataImportBusinessFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCsvDataImporterCanBeCreatedFromConfiguration()
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import-standard.csv');
        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType('import-type')
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $csvDataImporter = $this->tester->getFactory()->getCsvDataImporterFromConfig($dataImporterConfigurationTransfer);

        $this->assertInstanceOf(DataImporterInterface::class, $csvDataImporter);
    }
}

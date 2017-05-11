<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business;

use Codeception\Configuration;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataImportBusinessFactoryTest
 * Add your own group annotations below this line
 */
class DataImportBusinessFactoryTest extends Test
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

        $dataImportBusinessFactory = new DataImportBusinessFactory();
        $csvDataImporter = $dataImportBusinessFactory->getCsvDataImporterFromConfig($dataImporterConfigurationTransfer);

        $this->assertInstanceOf(DataImporterInterface::class, $csvDataImporter);
    }

}

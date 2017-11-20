<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Model
 * @group DataImporterCollectionTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataImporterCollectionTest extends Unit
{
    const DATA_IMPORTER_TYPE_A = 'data-importer-type-a';
    const DATA_IMPORTER_TYPE_B = 'data-importer-type-b';

    /**
     * @return void
     */
    public function testAddDataImporter()
    {
        $dataImporterCollection = $this->tester->getFactory()->createDataImporterCollection();
        $fluentInterface = $dataImporterCollection->addDataImporter($this->tester->getDataImporterMock(static::DATA_IMPORTER_TYPE_A));

        $this->assertInstanceOf(DataImporterCollectionInterface::class, $fluentInterface);
    }

    /**
     * @return void
     */
    public function testImportReturnsSuccessfulDataImportReportWhenAtLeastOneDataSetWasImported()
    {
        $dataImporterReportTransfer = new DataImporterReportTransfer();
        $dataImporterReportTransfer
            ->setIsSuccess(true)
            ->setImportedDataSetCount(1);

        $dataImporterCollection = $this->tester->getFactory()->createDataImporterCollection();
        $dataImporterCollection->addDataImporter($this->tester->getDataImporterMock(static::DATA_IMPORTER_TYPE_A, true, $dataImporterReportTransfer));

        $dataImporterReportTransfer = $dataImporterCollection->import();
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
    }
}

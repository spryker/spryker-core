<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollection;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Model
 * @group DataImporterCollectionTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\DataImportBusinessTester $tester
 */
class DataImporterCollectionTest extends Unit
{
    const DATA_IMPORTER_TYPE_A = 'data-importer-type-a';
    const DATA_IMPORTER_TYPE_B = 'data-importer-type-b';

    const DATA_IMPORTER_PLUGIN_TYPE_A = 'data-importer-plugin-type-a';
    const DATA_IMPORTER_PLUGIN_TYPE_B = 'data-importer-plugin-type-b';

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

    /**
     * @return void
     */
    public function testImporterPluginCanBeAddedAfterSpecificDataImporter()
    {
        $dataImportCollectionMock = $this->getDataImportCollectionMock();
        $dataImporterA = $this->tester->getDataImporterMock(static::DATA_IMPORTER_TYPE_A);
        $dataImporterB = $this->tester->getDataImporterMock(static::DATA_IMPORTER_TYPE_B);
        $dataImporterPluginA = $this->tester->getDataImporterPluginMock(static::DATA_IMPORTER_PLUGIN_TYPE_A);
        $dataImporterPluginB = $this->tester->getDataImporterPluginMock(static::DATA_IMPORTER_PLUGIN_TYPE_B);

        $dataImportCollectionMock->addDataImporter($dataImporterA);
        $dataImportCollectionMock->addDataImporter($dataImporterB);

        $dataImportCollectionMock->addDataImporterPlugins([
            [$dataImporterPluginA, static::DATA_IMPORTER_TYPE_A],
            $dataImporterPluginB,
        ]);

        $dataImportCollectionMock->expects($this->at(0))->method('executeDataImporter')->with($dataImporterA);
        $dataImportCollectionMock->expects($this->at(1))->method('executeDataImporter')->with($dataImporterPluginA);
        $dataImportCollectionMock->expects($this->at(2))->method('executeDataImporter')->with($dataImporterB);
        $dataImportCollectionMock->expects($this->at(3))->method('executeDataImporter')->with($dataImporterPluginB);

        $dataImportCollectionMock->import();
    }

    /**
     * @return void
     */
    public function testImporterPluginWillAddedAtTheEndIfAddAfterIsNotMatchingToAnyAppliedImporter()
    {
        $dataImportCollectionMock = $this->getDataImportCollectionMock();

        $dataImporterA = $this->tester->getDataImporterMock(static::DATA_IMPORTER_TYPE_A);
        $dataImporterB = $this->tester->getDataImporterMock(static::DATA_IMPORTER_TYPE_B);
        $dataImporterPluginA = $this->tester->getDataImporterPluginMock(static::DATA_IMPORTER_PLUGIN_TYPE_A);

        $dataImportCollectionMock->addDataImporter($dataImporterA);
        $dataImportCollectionMock->addDataImporter($dataImporterB);

        $dataImportCollectionMock->addDataImporterPlugins([
            [$dataImporterPluginA, 'catface'],
        ]);

        $dataImportCollectionMock->expects($this->at(0))->method('executeDataImporter')->with($dataImporterA);
        $dataImportCollectionMock->expects($this->at(1))->method('executeDataImporter')->with($dataImporterB);
        $dataImportCollectionMock->expects($this->at(2))->method('executeDataImporter')->with($dataImporterPluginA);

        $dataImportCollectionMock->import();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Business\Model\DataImporterPluginCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporter
     */
    protected function getDataImportCollectionMock()
    {
        $dataImportCollectionMockBuilder = $this->getMockBuilder(DataImporterCollection::class)
            ->setMethods(['executeDataImporter']);

        $dataImportCollectionMock = $dataImportCollectionMockBuilder->getMock();

        return $dataImportCollectionMock;
    }
}

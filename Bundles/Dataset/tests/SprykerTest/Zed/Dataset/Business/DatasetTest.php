<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Dataset\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Dataset
 * @group Business
 * @group DatasetTest
 * Add your own group annotations below this line
 */
class DatasetTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Dataset\DatasetBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSavesCsvData(): void
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);

        $this->assertInstanceOf(SpyDatasetEntityTransfer::class, $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertSame($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function testSaveDataset(): void
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);

        $this->assertInstanceOf(SpyDatasetEntityTransfer::class, $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertSame($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function testExistingDatasetName(): void
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $this->tester->getLocator()->dataset()->facade()->save($datasetEntityTransfer, $this->tester->createDatasetFilePathTransfer());
        $dashboardExist = $this->tester->getLocator()->dataset()->facade()->hasDatasetName($datasetEntityTransfer->getName());

        $this->assertTrue($dashboardExist);
    }

    /**
     * @return void
     */
    public function testExportToCsv()
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $this->tester->getLocator()->dataset()->facade()->save($datasetEntityTransfer, $this->tester->createDatasetFilePathTransfer());
        $datasetTransfer = $this->tester->getLocator()->dataset()->facade()->getDatasetModelByName($datasetEntityTransfer->getName());

        $dataContent = $this->tester->getLocator()->dataset()->facade()->exportToCsv($datasetTransfer);
        $originalFileContent = file_get_contents($this->tester->createDatasetFilePathTransfer()->getFilePath());

        $this->assertSame($dataContent, $originalFileContent);
    }

    /**
     * @return void
     */
    public function testExistingDatasetById()
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $datasetTransfer = $this->tester->getLocator()->dataset()->facade()->getDatasetModelById($datasetTransfer->getIdDataset());

        $this->assertInstanceOf(SpyDatasetEntityTransfer::class, $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertSame($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function testExistingDatasetByName()
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);

        $this->assertInstanceOf(SpyDatasetEntityTransfer::class, $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertSame($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDeleteDataset()
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $datasetId = $datasetTransfer->getIdDataset();
        $this->tester->getLocator()->dataset()->facade()->delete($datasetId);

        $this->expectException(DatasetNotFoundException::class);

        $this->tester->getLocator()->dataset()->facade()->getDatasetModelById($datasetId);
    }

    /**
     * @return void
     */
    public function testActivateDataset()
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $this->tester->getLocator()->dataset()->facade()->activateById($datasetTransfer->getIdDataset());
        $datasetTransfer = $this->tester->getLocator()->dataset()->facade()->getDatasetModelById($datasetTransfer->getIdDataset());

        $this->assertTrue($datasetTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeactivateDataset()
    {
        $datasetEntityTransfer = $this->tester->createDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $this->tester->getLocator()->dataset()->facade()->deactivateById($datasetTransfer->getIdDataset());
        $datasetTransfer = $this->tester->getLocator()->dataset()->facade()->getDatasetModelById($datasetTransfer->getIdDataset());

        $this->assertFalse($datasetTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameReturnsValidTransfer()
    {
        $datasetFilenameTransfer = $this->tester->getLocator()->dataset()->facade()->getFilenameByDatasetName('some name');

        $this->assertInstanceOf(DatasetFilenameTransfer::class, $datasetFilenameTransfer);
        $this->assertNotEmpty($datasetFilenameTransfer->getFilename());
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected function saveDatasetByTransfer(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $this->tester->getLocator()->dataset()->facade()->saveDataset($datasetEntityTransfer);

        return $this->tester->getLocator()->dataset()->facade()->getDatasetModelByName($datasetEntityTransfer->getName());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Dataset\Business;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyLocaleEntityTransfer;
use Spryker\Zed\Dataset\Business\DatasetFacade;
use Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException;
use Spryker\Zed\Locale\Business\LocaleFacade;

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
     * @var \Spryker\Zed\Dataset\Business\DatasetFacade
     */
    protected $facade;

    /**
     * @var \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected $datasetEntityTransfer;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->datasetEntityTransfer = new SpyDatasetEntityTransfer();
        $this->facade = new DatasetFacade();
        $this->filePath = Configuration::dataDir() . 'dashboard_data_file.csv';
        $this->localeFacade = new LocaleFacade();
    }

    /**
     * @return void
     */
    public function save()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\SpyDatasetEntityTransfer', $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertEquals($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function saveDataset()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\SpyDatasetEntityTransfer', $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertEquals($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function hasDatasetName()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $this->facade->save($datasetEntityTransfer, $this->filePath);
        $dashboardExist = $this->facade->hasDatasetName($datasetEntityTransfer->getName());

        $this->assertTrue($dashboardExist);
    }

    /**
     * @return void
     */
    public function getDatasetContent()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $dataContent = $this->facade->getDatasetContent($datasetTransfer);
        $originalFileContent = file_get_contents($this->filePath);

        $this->assertEquals($dataContent, $originalFileContent);
    }

    /**
     * @return void
     */
    public function getDatasetTransferById()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $datasetTransfer = $this->facade->getDatasetTransferById($datasetTransfer->getIdDataset());

        $this->assertInstanceOf('\Generated\Shared\Transfer\SpyDatasetEntityTransfer', $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertEquals($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function getDatasetTransferByName()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\SpyDatasetEntityTransfer', $datasetTransfer);
        $this->assertNotNull($datasetTransfer->getIdDataset());
        $this->assertEquals($datasetEntityTransfer->getName(), $datasetTransfer->getName());
    }

    /**
     * @return void
     */
    public function delete()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $datasetId = $datasetTransfer->getIdDataset();
        $this->facade->delete($datasetId);

        $this->expectException(DatasetNotFoundException::class);

        $this->facade->getDatasetTransferById($datasetId);
    }

    /**
     * @return void
     */
    public function activateById()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $this->facade->activateById($datasetTransfer->getIdDataset());
        $datasetTransfer = $this->facade->getDatasetTransferById($datasetTransfer->getIdDataset());

        $this->assertTrue($datasetTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function deactivateById()
    {
        $datasetEntityTransfer = $this->mockDatasetTransfer();
        $datasetTransfer = $this->saveDatasetByTransfer($datasetEntityTransfer);
        $this->facade->deactivateById($datasetTransfer->getIdDataset());
        $datasetTransfer = $this->facade->getDatasetTransferById($datasetTransfer->getIdDataset());

        $this->assertFalse($datasetTransfer->getIsActive());
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected function saveDatasetByTransfer(SpyDatasetEntityTransfer $datasetEntityTransfer)
    {
        $this->facade->saveDataset($datasetEntityTransfer);
        return $this->facade->getDatasetTransferByName($datasetEntityTransfer->getName());
    }

    /**
     * array $data
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected function mockDatasetTransfer()
    {
        $datasetEntity = new SpyDatasetEntityTransfer();
        $datasetEntity->setName(sprintf('Test Dashboard %s', rand(1, 999)));
        $datasetEntity->setIsActive(true);

        $this->addDatasetLocalizedAttributes($datasetEntity);

        return $datasetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetEntity
     *
     * @return void mockDatasetData
     */
    protected function addDatasetLocalizedAttributes(SpyDatasetEntityTransfer $datasetEntity)
    {
        $localizedAttributes = $this->localeFacade->getAvailableLocales();
        foreach ($localizedAttributes as $idLocale => $localizedAttribute) {
            $datasetLocalizedAttributesEntityTransfer = new SpyDatasetLocalizedAttributesEntityTransfer();
            $localeEntityTransfer = new SpyLocaleEntityTransfer();
            $localeEntityTransfer->setIdLocale($idLocale);
            $datasetLocalizedAttributesEntityTransfer->setLocale($localeEntityTransfer);
            $datasetLocalizedAttributesEntityTransfer->setTitle($localizedAttribute);
            $datasetEntity->addSpyDatasetLocalizedAttributess($datasetLocalizedAttributesEntityTransfer);
        }
    }
}

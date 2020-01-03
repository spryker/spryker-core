<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductScheduleDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\PriceProductScheduleDataImport\Communication\Plugin\PriceProductScheduleDataImportPlugin;
use Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductScheduleDataImport
 * @group Communication
 * @group Plugin
 * @group PriceProductScheduleDataImportPluginTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleDataImportPluginTest extends Unit
{
    public const ABSTRACT_SKU = 'foo';

    public const CONCRETE_SKU = 'foo-concrete';

    public const EXPECTED_IMPORT_COUNT = 8;

    /**
     * @var \SprykerTest\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsPriceProductSchedules(): void
    {
        //Assign
        $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => static::CONCRETE_SKU,
        ], [
            ProductAbstractTransfer::SKU => static::ABSTRACT_SKU,
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_price_schedule.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $priceProductScheduleDataImportPlugin = new PriceProductScheduleDataImportPlugin();

        //Act
        $dataImporterReportTransfer = $priceProductScheduleDataImportPlugin->import($dataImportConfigurationTransfer);

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of price product schedules is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        //Assign
        $priceProductScheduleDataImportPlugin = new PriceProductScheduleDataImportPlugin();

        //Act
        $importType = $priceProductScheduleDataImportPlugin->getImportType();

        //Assert
        $this->assertSame(PriceProductScheduleDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE_SCHEDULE, $importType);
    }
}

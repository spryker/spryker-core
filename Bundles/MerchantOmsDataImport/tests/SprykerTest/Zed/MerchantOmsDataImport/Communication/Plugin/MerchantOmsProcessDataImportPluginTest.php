<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantOmsDataImport\Communication\Plugin\MerchantOmsProcessDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOmsDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantOmsProcessDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantOmsProcessDataImportPluginTest extends Unit
{
    public const MERCHANT_KEY = 'merchant-profile-data-import-test-key';

    /**
     * @var \SprykerTest\Zed\MerchantOmsDataImport\MerchantOmsDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantOmsProcessTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureMerchantOmsProcessTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testMerchantOmsProcessImport(): void
    {
        // Arrange
        if (!$this->tester->findMerchantByKey(static::MERCHANT_KEY)) {
            $this->tester->haveMerchant([
                'merchant_key' => static::MERCHANT_KEY,
            ]);
        }

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant_oms_process.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $merchantOmsProcessDataImportPlugin = new MerchantOmsProcessDataImportPlugin();
        $dataImporterReportTransfer = $merchantOmsProcessDataImportPlugin->import($dataImportConfigurationTransfer);
        $merchantEntity = $this->tester->findMerchantByKey(static::MERCHANT_KEY);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertNotNull($merchantEntity->getFkMerchantOmsProcess());
        $this->tester->assertMerchantOmsDatabaseTablesContainsData();
    }
}

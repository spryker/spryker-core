<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantDataImport\Communication\Plugin\MerchantStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantStoreDataImportPluginTest extends Unit
{
    protected const MERCHANT_KEY = 'kudu-merchant-test';

    /**
     * @var \SprykerTest\Zed\MerchantDataImport\MerchantDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsMerchantStoreData(): void
    {
        // Arrange
        $this->tester->ensureMerchantStoreTableIsEmpty();
        $this->tester->deleteMerchantByKey(static::MERCHANT_KEY);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::MERCHANT_KEY => static::MERCHANT_KEY]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        //Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertMerchantStoreDatabaseTableContainsData($merchantTransfer->getIdMerchant());
    }
}

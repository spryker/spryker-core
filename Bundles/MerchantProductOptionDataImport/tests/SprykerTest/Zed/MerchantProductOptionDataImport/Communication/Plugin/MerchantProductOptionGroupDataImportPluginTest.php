<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOptionDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantProductOptionDataImport\Communication\Plugin\DataImport\MerchantProductOptionGroupDataImportPlugin;
use Spryker\Zed\MerchantProductOptionDataImport\MerchantProductOptionDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOptionDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProductOptionGroupDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductOptionGroupDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOptionDataImport\MerchantProductOptionDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_product_option_group.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProductOptionGroupDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertMerchantProductOptionGroupTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $MerchantProductOptionGroupDataImportPlugin = new MerchantProductOptionGroupDataImportPlugin();

        // Assert
        $this->assertSame(
            MerchantProductOptionDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OPTION_GROUP,
            $MerchantProductOptionGroupDataImportPlugin->getImportType()
        );
    }
}

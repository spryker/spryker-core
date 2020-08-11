<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ProductConfigurationDataImport\Communication\Plugin\ProductConfigurationDataImportPlugin;
use Spryker\Zed\ProductConfigurationDataImport\ProductConfigurationDataImportConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group ProductConfigurationDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationDataImportPluginTest extends Unit
{
    use DataCleanupHelperTrait;

    protected const PRODUCT_CONFIGURATION_TEST_SKU = 'product_configuration_test_sku';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationDataImport\ProductConfigurationDataImportBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->cleanProductConfigurationTable();

        $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => static::PRODUCT_CONFIGURATION_TEST_SKU,
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_configuration.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productConfigurationDataImportPlugin = new ProductConfigurationDataImportPlugin();
        $productConfigurationDataImportPlugin->import($dataImportConfigurationTransfer);

        $productConfigurationQuery = $this->tester->getProductConfigurationQuery();

        $this->assertTrue(($productConfigurationQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductNotFound(): void
    {
        $this->tester->cleanProductConfigurationTable();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_configuration_product_not_exists.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $productConfigurationDataImportPlugin = new ProductConfigurationDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find product by sku');

        $productConfigurationDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productConfigurationDataImportPlugin = new ProductConfigurationDataImportPlugin();
        $this->assertSame(ProductConfigurationDataImportConfig::IMPORT_TYPE_PRODUCT_CONFIGURATION, $productConfigurationDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getDataCleanupHelper()->_addCleanup(function (): void {
            $this->tester->cleanProductConfigurationTable();
        });
    }
}

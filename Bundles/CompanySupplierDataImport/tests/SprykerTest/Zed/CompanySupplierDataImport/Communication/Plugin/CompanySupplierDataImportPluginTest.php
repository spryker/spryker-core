<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanySupplier\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanySupplierDataImport\Communication\Plugin\CompanySupplierDataImportPlugin;
use Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySupplier
 * @group Communication
 * @group Plugin
 * @group CompanySupplierDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanySupplierDataImportPluginTest extends Unit
{
    protected const COMPANY_KEY = 'spryker';
    protected const PRODUCT_SKU = 'spryker_product';

    /**
     * @var \SprykerTest\Zed\CompanySupplierDataImport\CompanySupplierDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanySupplierRelations(): void
    {
        $this->tester->ensureDatabaseTableCompanySupplierToProductIsEmpty();
        $this->tester->haveCompany(['key' => static::COMPANY_KEY]);
        if (!$this->tester->isProductCreated(static::PRODUCT_SKU)) {
            $this->tester->haveProduct(['sku' => static::PRODUCT_SKU]);
        }

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_supplier.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $companyDataImportPlugin = new CompanySupplierDataImportPlugin();

        $dataImporterReportTransfer = $companyDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableCompanySupplierToProductContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyDataImportPlugin = new CompanySupplierDataImportPlugin();
        $this->assertSame(CompanySupplierDataImportConfig::IMPORT_TYPE_COMPANY_SUPPLIER, $companyDataImportPlugin->getImportType());
    }
}

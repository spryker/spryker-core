<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySupplier\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanySupplierDataImport\Communication\Plugin\CompanySupplierProductPriceDataImportPlugin;
use Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanySupplier
 * @group Communication
 * @group Plugin
 * @group CompanySupplierProductPriceDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanySupplierProductPriceDataImportPluginTest extends Unit
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
    public function testImportImportsCompanySupplierProductPrice(): void
    {
        $this->tester->ensureDatabaseTablePriceProductIsEmpty();
        $this->tester->haveCompany(['key' => static::COMPANY_KEY]);
        if (!$this->tester->isProductCreated(static::PRODUCT_SKU)) {
            $this->tester->haveProduct(['sku' => static::PRODUCT_SKU]);
        }

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_supplier_product_price.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $companyDataImportPlugin = new CompanySupplierProductPriceDataImportPlugin();

        $dataImporterReportTransfer = $companyDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertCompanySupplierProductPriceImported();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyDataImportPlugin = new CompanySupplierProductPriceDataImportPlugin();
        $this->assertSame(CompanySupplierDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE, $companyDataImportPlugin->getImportType());
    }
}

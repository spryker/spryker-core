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
use Spryker\Zed\CompanySupplierDataImport\Communication\Plugin\CompanyTypeDataImportPlugin;
use Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanySupplier
 * @group Communication
 * @group Plugin
 * @group CompanyTypeDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyTypeDataImportPluginTest extends Unit
{
    protected const COMPANY_KEY = 'spryker';

    /**
     * @var \SprykerTest\Zed\CompanySupplierDataImport\CompanySupplierDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyType(): void
    {
        $this->tester->ensureDatabaseTableCompanyTypeIsEmpty();
        $this->tester->haveCompany(['key' => static::COMPANY_KEY]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_type.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $companyDataImportPlugin = new CompanyTypeDataImportPlugin();

        $dataImporterReportTransfer = $companyDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertCompanyTypeImported();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyDataImportPlugin = new CompanyTypeDataImportPlugin();
        $this->assertSame(CompanySupplierDataImportConfig::IMPORT_TYPE_COMPANY_TYPE, $companyDataImportPlugin->getImportType());
    }
}

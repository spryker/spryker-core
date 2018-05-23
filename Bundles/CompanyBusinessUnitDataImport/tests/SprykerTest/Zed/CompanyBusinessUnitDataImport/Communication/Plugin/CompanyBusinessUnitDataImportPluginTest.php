<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyBusinessUnitDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyBusinessUnit(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->tester->haveCompany(['key' => 'spryker']);

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer('import/company_business_unit.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $dataImporterReportTransfer = $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer('import/company_business_unit_with_invalid_company.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company by key "invalid company"');
        $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenParentBusinessUnitNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer('import/company_business_unit_with_invalid_parent.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find business unit by key "invalid parent"');
        $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $this->assertSame(CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT, $companyBusinessUnitDataImportPlugin->getImportType());
    }

    /**
     * @param string $file
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImportConfigurationTransfer(string $file): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $file);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }
}

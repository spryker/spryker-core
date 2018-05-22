<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig;
use Spryker\Zed\BusinessOnBehalfDataImport\Communication\Plugin\BusinessOnBehalfDataImportPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group BusinessOnBehalfDataImport
 * @group Communication
 * @group Plugin
 * @group BusinessOnBehalfDataImportPluginTest
 * Add your own group annotations below this line
 */
class BusinessOnBehalfDataImportPluginTest extends Unit
{
    protected const COMPANY_KEY = 'test-company';
    protected const BUSINESS_UNIT_KEY = 'test-business-unit';

    /**
     * @var \SprykerTest\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->prepareTestData();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $businessOnBehalfDataImportPlugin = new BusinessOnBehalfDataImportPlugin();
        $dataImporterReportTransfer = $businessOnBehalfDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
        $this->tester->assertDatabaseTableContainsCorrectData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $businessOnBehalfDataImportPlugin = new BusinessOnBehalfDataImportPlugin();
        $this->assertSame(BusinessOnBehalfDataImportConfig::IMPORT_TYPE_COMPANY_USER, $businessOnBehalfDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function prepareTestData(): void
    {
        $this->tester->prepareTestData();
        $companyTransfer = $this->tester->haveCompany(['key' => static::COMPANY_KEY]);
        $this->tester->haveCompanyBusinessUnit([
            'key' => static::BUSINESS_UNIT_KEY,
            'fkCompany' => $companyTransfer->getIdCompany(),
        ]);
    }
}

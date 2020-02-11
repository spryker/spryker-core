<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin\CompanyUnitAddressLabelRelationDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddressLabelDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyUnitAddressLabelRelationDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressLabelRelationDataImportPluginTest extends Unit
{
    protected const COMPANY_ADDRESS_KEY_1 = 'spryker-address-1';
    protected const COMPANY_ADDRESS_KEY_2 = 'spryker-address-2';

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyUnitAddressLabelRelation(): void
    {
        $this->tester->ensureRelationTableIsEmpty();
        $this->tester->ensureCompanyUnitAddressWithKeyDoesNotExist(static::COMPANY_ADDRESS_KEY_1);
        $this->tester->ensureCompanyUnitAddressWithKeyDoesNotExist(static::COMPANY_ADDRESS_KEY_2);
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COUNTRY => $this->tester->haveCountry()->getIdCountry(),
            CompanyUnitAddressTransfer::KEY => static::COMPANY_ADDRESS_KEY_1,
        ]);
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COUNTRY => $this->tester->haveCountry()->getIdCountry(),
            CompanyUnitAddressTransfer::KEY => static::COMPANY_ADDRESS_KEY_2,
        ]);
        $this->tester->haveCompanyUnitAddressLabel(['name' => 'label-1']);
        $this->tester->haveCompanyUnitAddressLabel(['name' => 'label-2']);

        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/company_unit_address_label_relation.csv');

        $companyUnitAddressLabelDataImportPlugin = new CompanyUnitAddressLabelRelationDataImportPlugin();
        $dataImporterReportTransfer = $companyUnitAddressLabelDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyUnitAddressNotFound(): void
    {
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/company_unit_address_label_relation_with_invalid_company_unit_address.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyUnitAddressDataImportPlugin = new CompanyUnitAddressLabelRelationDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find CompanyUnitAddress with key "invalid-address-key".');

        $companyUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyUnitAddressLabelNotFound(): void
    {
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/company_unit_address_label_relation_with_invalid_company_unit_address_label.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyUnitAddressDataImportPlugin = new CompanyUnitAddressLabelRelationDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find CompanyUnitAddressLabel with name "invalid-label-name".');

        $companyUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyUnitAddressLabelRelationDataImportPlugin = new CompanyUnitAddressLabelRelationDataImportPlugin();
        $this->assertSame(CompanyUnitAddressLabelDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS_LABEL_RELATION, $companyUnitAddressLabelRelationDataImportPlugin->getImportType());
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImporterReaderConfigurationTransfer(string $filePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $filePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }
}

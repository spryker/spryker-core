<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantRelationshipDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\MerchantRelationshipDataImport\Business\MerchantRelationshipDataImportBusinessFactory;
use Spryker\Zed\MerchantRelationshipDataImport\Business\MerchantRelationshipDataImportFacade;
use Spryker\Zed\MerchantRelationshipDataImport\Communication\Plugin\MerchantRelationshipDataImportPlugin;
use Spryker\Zed\MerchantRelationshipDataImport\MerchantRelationshipDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantRelationshipDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipDataImport\MerchantRelationshipDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateMerchantRelationshipRelations();

        $this->tester->assertDatabaseTableIsEmpty();

        $this->tester->truncateMerchantRelations();
        $this->tester->truncateCompanyBusinessUnitRelations();

        $this->createRelatedData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_relationship.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantRelationshipDataImportPlugin();
        $pluginReflection = new ReflectionClass($dataImportPlugin);

        $facadePropertyReflection = $pluginReflection->getParentClass()->getProperty('facade');
        $facadePropertyReflection->setAccessible(true);
        $facadePropertyReflection->setValue($dataImportPlugin, $this->getFacadeMock());

        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantRelationshipDataImportPlugin();
        $this->assertSame(MerchantRelationshipDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP, $dataImportPlugin->getImportType());
    }

    /**
     * @param int $idCompany
     * @param string $key
     *
     * @return void
     */
    protected function createCompanyBusinessUnit(int $idCompany, string $key): void
    {
        $seedData = [
            'key' => $key,
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];

        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();

        $createdTransfer = $this->tester->getCompanyBusinessUnitFacade()
            ->create($businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $this->assertNotNull($createdTransfer->getIdCompanyBusinessUnit());
    }

    /**
     * @return void
     */
    protected function createRelatedData(): void
    {
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $this->createCompanyBusinessUnit($idCompany, 'ttest-business-unit-1');
        $this->createCompanyBusinessUnit($idCompany, 'ttest-business-unit-2');
        $this->createCompanyBusinessUnit($idCompany, 'ttest-business-unit-3');

        $this->tester->haveMerchant([
            'merchantKey' => 'oryx-merchant-test',
        ]);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipDataImport\Business\MerchantRelationshipDataImportFacade
     */
    public function getFacadeMock()
    {
        $factoryMock = $this->getMockBuilder(MerchantRelationshipDataImportBusinessFactory::class)
            ->setMethods(
                [
                    'createTransactionAwareDataSetStepBroker',
                    'getConfig',
                ]
            )
            ->getMock();

        $factoryMock
            ->method('createTransactionAwareDataSetStepBroker')
            ->willReturn(new DataSetStepBroker());

        $factoryMock->method('getConfig')
            ->willReturn(new MerchantRelationshipDataImportConfig());

        $facade = new MerchantRelationshipDataImportFacade();
        $facade->setFactory($factoryMock);

        return $facade;
    }
}

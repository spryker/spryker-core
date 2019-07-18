<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\PriceProductMerchantRelationshipDataImportBusinessFactory;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\PriceProductMerchantRelationshipDataImportFacade;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Communication\Plugin\PriceProductMerchantRelationshipDataImportPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductMerchantRelationshipDataImport
 * @group Communication
 * @group Plugin
 * @group PriceProductMerchantRelationshipDataImportPluginTest
 * Add your own group annotations below this line
 */
class PriceProductMerchantRelationshipDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $PriceProductMerchantRelationshipDataImportPlugin = new PriceProductMerchantRelationshipDataImportPlugin();
        $this->assertSame(
            PriceProductMerchantRelationshipDataImportConfig::IMPORT_TYPE_PRICE_PRODUCT_MERCHANT_RELATIONSHIP,
            $PriceProductMerchantRelationshipDataImportPlugin->getImportType()
        );
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateMerchantRelationshipRelations();
        $this->tester->assertDatabaseTableIsEmpty();

        $this->createRelatedData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/price_product_merchant_relationship.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $dataImportConfigurationTransfer->setThrowException(true);

        $PriceProductMerchantRelationshipDataImportPlugin = new PriceProductMerchantRelationshipDataImportPlugin();
        $pluginReflection = new ReflectionClass($PriceProductMerchantRelationshipDataImportPlugin);

        $facadePropertyReflection = $pluginReflection->getParentClass()->getProperty('facade');
        $facadePropertyReflection->setAccessible(true);
        $facadePropertyReflection->setValue($PriceProductMerchantRelationshipDataImportPlugin, $this->getFacadeMock());

        $dataImporterReportTransfer = $PriceProductMerchantRelationshipDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    protected function createRelatedData(): void
    {
        $idMerchant = $this->tester->haveMerchant()->getIdMerchant();

        $this->createMerchantRelationship($idMerchant, 'mr-test-001');
        $this->createMerchantRelationship($idMerchant, 'mr-test-002');
        $this->createMerchantRelationship($idMerchant, 'mr-test-003');
    }

    /**
     * @param int|string $idMerchant
     * @param string $key
     *
     * @return void
     */
    protected function createMerchantRelationship(int $idMerchant, string $key): void
    {
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveMerchantRelationship([
            'merchantRelationshipKey' => $key,
            'fkMerchant' => $idMerchant,
            'fkCompanyBusinessUnit' => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\PriceProductMerchantRelationshipDataImportFacade
     */
    public function getFacadeMock()
    {
        $factoryMock = $this->getMockBuilder(PriceProductMerchantRelationshipDataImportBusinessFactory::class)
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
            ->willReturn(new PriceProductMerchantRelationshipDataImportConfig());

        $facade = new PriceProductMerchantRelationshipDataImportFacade();
        $facade->setFactory($factoryMock);

        return $facade;
    }
}

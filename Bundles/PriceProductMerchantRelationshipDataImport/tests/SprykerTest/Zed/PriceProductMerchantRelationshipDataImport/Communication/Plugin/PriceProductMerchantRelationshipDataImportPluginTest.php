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
            $PriceProductMerchantRelationshipDataImportPlugin->getImportType(),
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
        $this->tester->mockFactoryMethod('createTransactionAwareDataSetStepBroker', new DataSetStepBroker());
        $facadePropertyReflection->setValue($PriceProductMerchantRelationshipDataImportPlugin, $this->tester->getFacade());

        $dataImporterReportTransfer = $PriceProductMerchantRelationshipDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    protected function createRelatedData(): void
    {
        //this data is the data needed for the import files under _data/import
        $abstractSkus = [
            '353241',
            '49872',
        ];
        foreach ($abstractSkus as $abstractSku) {
            $this->tester->haveProductAbstract(['sku' => $abstractSku]);
        }

        $concreteSkus = [
            '12512412',
            '12421512214',
            '4523424',
            '51242135',
        ];
        foreach ($concreteSkus as $sku) {
            $this->tester->haveProduct(['sku' => $sku]);
        }
        $idMerchant = $this->tester->haveMerchant()->getIdMerchant();

        $this->createMerchantRelationship($idMerchant, 'mr-test-12001');
        $this->createMerchantRelationship($idMerchant, 'mr-test-12002');
        $this->createMerchantRelationship($idMerchant, 'mr-test-12003');
    }

    /**
     * @param string|int $idMerchant
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
}

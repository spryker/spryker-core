<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\MerchantDataImport\Business\MerchantDataImportBusinessFactory;
use Spryker\Zed\MerchantDataImport\Business\MerchantDataImportFacade;
use Spryker\Zed\MerchantDataImport\Business\MerchantDataImportFacadeInterface;
use Spryker\Zed\MerchantDataImport\Communication\Plugin\MerchantAddressDataImportPlugin;
use Spryker\Zed\MerchantDataImport\MerchantDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantAddressDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantAddressDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantDataImport\MerchantDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportAddressesImportsData(): void
    {
        $this->tester->truncateMerchantRelations();
        $this->tester->truncateMerchantAddressRelations();

        $this->tester->assertMerchantAddressDatabaseTableIsEmpty();

        $this->tester->haveMerchant([
            'merchant_key' => 'kudu-merchant-1',
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_address.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantAddressDataImportPlugin();
        $pluginReflection = new ReflectionClass($dataImportPlugin);

        $facadePropertyReflection = $pluginReflection->getParentClass()->getProperty('facade');
        $facadePropertyReflection->setAccessible(true);
        $facadePropertyReflection->setValue($dataImportPlugin, $this->getFacadeMock());

        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertMerchantAddressDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantAddressDataImportPlugin();
        $this->assertSame(MerchantDataImportConfig::IMPORT_TYPE_MERCHANT_ADDRESS, $dataImportPlugin->getImportType());
    }

    /**
     * @return \Spryker\Zed\MerchantDataImport\Business\MerchantDataImportFacade
     */
    public function getFacadeMock(): MerchantDataImportFacadeInterface
    {
        $factoryMock = $this->getMockBuilder(MerchantDataImportBusinessFactory::class)
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
            ->willReturn(new MerchantDataImportConfig());

        $facade = new MerchantDataImportFacade();
        $facade->setFactory($factoryMock);

        return $facade;
    }
}

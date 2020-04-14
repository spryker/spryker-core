<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProfileDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfileDataImportBusinessFactory;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfileDataImportFacade;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfileDataImportFacadeInterface;
use Spryker\Zed\MerchantProfileDataImport\Communication\Plugin\MerchantProfileAddressDataImportPlugin;
use Spryker\Zed\MerchantProfileDataImport\MerchantProfileDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfileDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProfileAddressDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProfileAddressDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfileDataImport\MerchantProfileDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportAddressesImportsData(): void
    {
        $this->tester->truncateMerchantRelations();
        $this->tester->truncateMerchantProfileAddressRelations();

        $this->tester->assertMerchantProfileAddressDatabaseTableIsEmpty();

        $merchantTransfer = $this->tester->haveMerchant([
            'merchant_key' => 'kudu-merchant-1',
        ]);
        $this->tester->haveMerchantProfile($merchantTransfer);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_address.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProfileAddressDataImportPlugin();
        $pluginReflection = new ReflectionClass($dataImportPlugin);

        $facadePropertyReflection = $pluginReflection->getParentClass()->getProperty('facade');
        $facadePropertyReflection->setAccessible(true);
        $facadePropertyReflection->setValue($dataImportPlugin, $this->getFacadeMock());

        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertMerchantProfileAddressDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantProfileAddressDataImportPlugin();
        $this->assertSame(MerchantProfileDataImportConfig::IMPORT_TYPE_MERCHANT_PROFILE_ADDRESS, $dataImportPlugin->getImportType());
    }

    /**
     * @return \Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfileDataImportFacade
     */
    public function getFacadeMock(): MerchantProfileDataImportFacadeInterface
    {
        $factoryMock = $this->getMockBuilder(MerchantProfileDataImportBusinessFactory::class)
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
            ->willReturn(new MerchantProfileDataImportConfig());

        $facade = new MerchantProfileDataImportFacade();
        $facade->setFactory($factoryMock);

        return $facade;
    }
}

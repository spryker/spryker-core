<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PriceProductDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportBusinessFactory;
use Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportFacade;
use Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportFacadeInterface;
use Spryker\Zed\PriceProductDataImport\Communication\Plugin\PriceProductDataImportPlugin;
use Spryker\Zed\PriceProductDataImport\PriceProductDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductDataImport
 * @group Communication
 * @group Plugin
 * @group PriceProductDataImportPluginTest
 * Add your own group annotations below this line
 */
class PriceProductDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductDataImport\PriceProductDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsPriceProduct(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_price.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $priceProductDataImportPlugin = $this->createPriceProductDataImportPlugin();
        $dataImporterReportTransfer = $priceProductDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $priceProductDataImportPlugin = new PriceProductDataImportPlugin();
        $this->assertSame(PriceProductDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE, $priceProductDataImportPlugin->getImportType());
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface
     */
    protected function createPriceProductDataImportPlugin(): DataImportPluginInterface
    {
        return (new PriceProductDataImportPlugin())->setFacade($this->getPriceProductDataImportFacade());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportFacadeInterface
     */
    protected function getPriceProductDataImportFacade(): PriceProductDataImportFacadeInterface
    {
        return (new PriceProductDataImportFacade())->setFactory($this->createPriceProductDataImportBusinessFactoryMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory
     */
    protected function createPriceProductDataImportBusinessFactoryMock()
    {
        $priceProductDataImportBusinessFactoryMock = $this->getMockBuilder(PriceProductDataImportBusinessFactory::class)->getMock();
        $priceProductDataImportBusinessFactoryMock->method('createTransactionAwareDataSetStepBroker')->willReturn($this->createTransactionAwareDataSetStepBroker());

        return $priceProductDataImportBusinessFactoryMock;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    protected function createTransactionAwareDataSetStepBroker(): DataSetStepBrokerInterface
    {
        return new DataSetStepBroker();
    }
}

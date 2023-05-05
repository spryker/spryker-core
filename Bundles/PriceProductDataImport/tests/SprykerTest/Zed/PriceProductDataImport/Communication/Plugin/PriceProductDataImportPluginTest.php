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
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\PriceProductDataImport\Business\Model\PriceProductWriterStep;
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
     * @var bool
     */
    protected static $neededDataAdded = false;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$neededDataAdded) {
            $concreteSkus = [
                '167254716',
                '65741521',
                '67512741',
                '678235723',
                '425124211',
            ];

            foreach ($concreteSkus as $concreteSku) {
                $this->tester->haveProduct(['sku' => $concreteSku]);
            }

            $abstractSkus = [
                '8761248',
                '1872641',
                '164167',
                '67154175',
                '16754172',
                '12531757',
                '12475162',
                '2475128',
            ];
            foreach ($abstractSkus as $abstractSku) {
                $this->tester->haveProductAbstract(['sku' => $abstractSku]);
            }

            static::$neededDataAdded = true;
        }
    }

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
     * @return \Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportFacadeInterface
     */
    protected function getPriceProductDataImportFacade(): PriceProductDataImportFacadeInterface
    {
        return (new PriceProductDataImportFacade())->setFactory($this->createPriceProductDataImportBusinessFactoryMock());
    }

    /**
     * @return \Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPriceProductDataImportBusinessFactoryMock(): PriceProductDataImportBusinessFactory
    {
        $priceProductDataImportBusinessFactoryMock = $this->getMockBuilder(PriceProductDataImportBusinessFactory::class)->getMock();
        $priceProductDataImportBusinessFactoryMock->method('createPriceProductDataImport')->willReturn($this->createPriceProductDataImport());

        return $priceProductDataImportBusinessFactoryMock;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createPriceProductDataImport()
    {
        $originalPriceProductDataImportBusinessFactory = $this->getOriginalPriceProductDataImportBusinessFactory();

        $dataImporter = $originalPriceProductDataImportBusinessFactory->getCsvDataImporterFromConfig(
            $this->getPriceProductDataImportConfig()->getPriceProductDataImporterConfiguration(),
        );

        $dataSetStepBroker = $originalPriceProductDataImportBusinessFactory->createDataSetStepBroker();
        $dataSetStepBroker->addStep($originalPriceProductDataImportBusinessFactory->createAbstractSkuToIdProductAbstractStep());
        $dataSetStepBroker->addStep($originalPriceProductDataImportBusinessFactory->createConcreteSkuToIdProductStep());
        $dataSetStepBroker->addStep($originalPriceProductDataImportBusinessFactory->createStoreToIdStoreStep());
        $dataSetStepBroker->addStep($originalPriceProductDataImportBusinessFactory->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker->addStep($originalPriceProductDataImportBusinessFactory->createPreparePriceDataStep());
        $dataSetStepBroker->addStep(new PriceProductWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\PriceProductDataImport\Business\PriceProductDataImportBusinessFactory
     */
    protected function getOriginalPriceProductDataImportBusinessFactory(): PriceProductDataImportBusinessFactory
    {
        return new PriceProductDataImportBusinessFactory();
    }

    /**
     * @return \Spryker\Zed\PriceProductDataImport\PriceProductDataImportConfig
     */
    protected function getPriceProductDataImportConfig(): PriceProductDataImportConfig
    {
        return new PriceProductDataImportConfig();
    }
}

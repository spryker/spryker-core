<?php

namespace SprykerTest\Zed\ProductBarcode\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\BarcodeDependencyProvider;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBarcode
 * @group Business
 * @group Facade
 * @group ProductBarcodeFacadeTest
 * Add your own group annotations below this line
 *
 * @group Barcode
 * @group Product
 * @property \SprykerTest\Zed\ProductBarcode\ProductBarcodeBusinessTester $tester
 */
class ProductBarcodeFacadeTest extends Test
{
    protected const GENERATED_CODE = 'generated code';
    protected const GENERATED_ENCODING = 'generated encoding';

    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected $barcodePlugin;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->barcodePlugin = $this->getBarcodePluginMock();
        $this->tester->setDependency(BarcodeDependencyProvider::PLUGINS_BARCODE_GENERATOR, [
            $this->barcodePlugin,
        ]);
    }

    /**
     * @return void
     */
    public function testGenerateBarcode()
    {
        $productConcreteTransfer = $this->tester->getProductConcrete();

        $barcodeResponseTransfer = $this->getFacade()
            ->generateBarcode($productConcreteTransfer, get_class($this->barcodePlugin));

        $this->assertSame(static::GENERATED_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testGenerateBarcodeUseProductSku()
    {
        $productConcreteTransfer = $this->tester->getProductConcrete();

        $barcodeResponseTransfer = $this->getFacade()
            ->generateBarcode($productConcreteTransfer, get_class($this->barcodePlugin));

        $this->assertSame($productConcreteTransfer->getSku(), $barcodeResponseTransfer->getCode());
    }

    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade(): ProductBarcodeFacadeInterface
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function getBarcodePluginMock(): BarcodeGeneratorPluginInterface
    {
        $barcodePlugin = $this->getMockBuilder(BarcodeGeneratorPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $barcodePlugin
            ->expects($this->once())
            ->method('generate')
            ->willReturnCallback(
                function (string $code) {
                    return (new BarcodeResponseTransfer())
                        ->setCode($code)
                        ->setEncoding(static::GENERATED_ENCODING);
                }
            );

        return $barcodePlugin;
    }
}

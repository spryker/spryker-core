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
 */
class ProductBarcodeFacadeTest extends Test
{
    protected const GENERATED_CODE = 'generated code';
    protected const GENERATED_ENCODING = 'generated encoding';

    /**
     * @var \SprykerTest\Zed\ProductBarcode\ProductBarcodeBusinessTester
     */
    protected $tester;

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

        $this->assertSame(static::GENERATED_CODE, $barcodeResponseTransfer->getCode());
        $this->assertSame(static::GENERATED_ENCODING, $barcodeResponseTransfer->getEncoding());
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

        $barcodeTransfer = (new BarcodeResponseTransfer())
            ->setCode(static::GENERATED_CODE)
            ->setEncoding(static::GENERATED_ENCODING);

        $barcodePlugin
            ->expects($this->once())
            ->method('generate')
            ->willReturn($barcodeTransfer);

        return $barcodePlugin;
    }
}

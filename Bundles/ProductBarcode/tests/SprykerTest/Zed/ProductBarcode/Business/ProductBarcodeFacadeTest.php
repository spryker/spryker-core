<?php

namespace SprykerTest\Zed\ProductBarcode\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\BarcodeDependencyProvider;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

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
        $this->tester->setDependency(BarcodeDependencyProvider::BARCODE_PLUGINS, [
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

        $this->assertEquals(static::GENERATED_CODE, $barcodeResponseTransfer->getCode());
    }

    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected function getBarcodePluginMock()
    {
        $barcodePlugin = $this->getMockBuilder(BarcodeGeneratorPluginInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['generate'])
            ->getMock();

        $barcodeTransfer = (new BarcodeResponseTransfer())
            ->setCode(static::GENERATED_CODE)
            ->setEncoding(static::GENERATED_ENCODING);

        $barcodePlugin->method('generate')
            ->willReturn($barcodeTransfer);

        return $barcodePlugin;
    }
}

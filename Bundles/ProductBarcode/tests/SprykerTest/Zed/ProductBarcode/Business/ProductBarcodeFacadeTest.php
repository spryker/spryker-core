<?php

namespace SprykerTest\Zed\ProductBarcode\Business;

use Codeception\TestCase\Test;
use Spryker\Service\Barcode\BarcodeDependencyProvider;

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
    protected const GENERATED_ENCODING = 'data:image/png;base64';

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

        $this->barcodePlugin = $this->tester->getBarcodePluginMock();

        $this->tester->setDependency(BarcodeDependencyProvider::PLUGINS_BARCODE_GENERATOR, [
            $this->barcodePlugin,
        ]);
    }

    /**
     * @return void
     */
    public function testGenerateBarcodeUsingProductConcreteTransfer()
    {
        $productConcreteTransfer = $this->tester->getProductConcrete();

        $barcodeResponseTransfer = $this->tester->getFacade()
            ->generateBarcode($productConcreteTransfer, get_class($this->barcodePlugin));

        $this->assertSame($productConcreteTransfer->getSku(), $barcodeResponseTransfer->getCode());
        $this->assertSame(static::GENERATED_ENCODING, $barcodeResponseTransfer->getEncoding());
    }
}

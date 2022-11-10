<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBarcode\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
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
class ProductBarcodeFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const BARCODE_GENERATION_SOURCE_TEXT = 'generated text';

    /**
     * @var string
     */
    protected const BARCODE_GENERATION_ENCODING = 'data:image/png;base64';

    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected $barcodePlugin;

    /**
     * @var \Generated\Shared\DataBuilder\ProductConcreteBuilder
     */
    protected $productConcreteBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->barcodePlugin = $this
            ->tester
            ->getBarcodePluginMock();

        $this->productConcreteBuilder = new ProductConcreteBuilder();

        $this->tester->setDependency(BarcodeDependencyProvider::PLUGINS_BARCODE_GENERATOR, [
            $this->barcodePlugin,
        ]);
    }

    /**
     * @return void
     */
    public function testBarcodeGenerationWithoutPluginSpecifiedReturnsCorrectData(): void
    {
        $product = $this->productConcreteBuilder->build();

        $barcodeResponseTransfer = $this
            ->tester
            ->generateBarcode($product, get_class($this->barcodePlugin));

        $this->assertInstanceOf(BarcodeResponseTransfer::class, $barcodeResponseTransfer);
        $this->assertSame($product->getSku(), $barcodeResponseTransfer->getCode());
        $this->assertSame(static::BARCODE_GENERATION_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testBarcodeGenerationWithMockPluginSpecifiedReturnsCorrectData(): void
    {
        $product = $this->productConcreteBuilder->build();

        $barcodeResponseTransfer = $this
            ->tester
            ->generateBarcode($product, get_class($this->barcodePlugin));

        $this->assertInstanceOf(BarcodeResponseTransfer::class, $barcodeResponseTransfer);
        $this->assertSame($product->getSku(), $barcodeResponseTransfer->getCode());
        $this->assertSame(static::BARCODE_GENERATION_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testBarcodeGenerationUsesProductDataFromDatabaseAsFallback(): void
    {
        $existingProduct = $this->tester->haveProduct();
        $existingProductSku = $existingProduct->getSku();

        $existingProduct->setSku('');

        $barcodeResponseTransfer = $this
            ->tester
            ->generateBarcode($existingProduct, get_class($this->barcodePlugin));

        $this->assertSame($existingProductSku, $barcodeResponseTransfer->getCode());
        $this->assertSame(static::BARCODE_GENERATION_ENCODING, $barcodeResponseTransfer->getEncoding());
    }
}

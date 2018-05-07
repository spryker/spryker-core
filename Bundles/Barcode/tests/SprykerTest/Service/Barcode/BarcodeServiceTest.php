<?php

namespace SprykerTest\Service\Barcode;

use Codeception\TestCase\Test;
use Spryker\Service\Barcode\BarcodeDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Barcode
 * @group BarcodeServiceTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Service\Barcode\BarcodeServiceTester $tester
 */
class BarcodeServiceTest extends Test
{
    protected const GENERATION_SEED = 'seed for generation';
    protected const GENERATED_CODE = 'generated string';
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
    public function testGenerateBarcode()
    {
        $barcodeResponseTransfer = $this->tester->getBarcodeService()
            ->generateBarcode(static::GENERATION_SEED, get_class($this->barcodePlugin));

        $this->assertSame(static::GENERATED_CODE, $barcodeResponseTransfer->getCode());
    }
}

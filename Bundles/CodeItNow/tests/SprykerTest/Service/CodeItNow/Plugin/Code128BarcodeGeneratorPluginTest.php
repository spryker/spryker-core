<?php

namespace SprykerTest\Service\CodeItNow\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\CodeItNow\Plugin\Code128BarcodeGeneratorPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group CodeItNow
 * @group Plugin
 * @group Code128BarcodeGeneratorPluginTest
 * Add your own group annotations below this line
 */
class Code128BarcodeGeneratorPluginTest extends Unit
{
    protected const CODE128_BARCODE_GENERATOR_PLUGIN_ENCODING = 'data:image/png;base64';
    protected const CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT = 'generated text';
    protected const CODE128_BARCODE_GENERATOR_PLUGIN_GENERATED_TEXT = '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';

    /**
     * @return void
     */
    public function testCode128BarcodeGeneratorPluginCreated(): void
    {
        $plugin = new Code128BarcodeGeneratorPlugin();
        $this->assertInstanceOf(Code128BarcodeGeneratorPlugin::class, $plugin);
    }

    /**
     * @return void
     */
    public function testCode128BarcodeGeneration(): void
    {
        $plugin = new Code128BarcodeGeneratorPlugin();
        $plugin->generate(static::CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testCode128BarcodeGeneratorPluginReturnsBarcodeResponseTransfer(): void
    {
        $plugin = new Code128BarcodeGeneratorPlugin();
        $barcodeResponseTransfer = $plugin->generate(static::CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT);

        $this->assertInstanceOf(BarcodeResponseTransfer::class, $barcodeResponseTransfer);
    }

    /**
     * @return void
     */
    public function testCode128BarcodeGeneratorPluginReturnsNotEmptyBarcodeResponseTransfer(): void
    {
        $plugin = new Code128BarcodeGeneratorPlugin();
        $barcodeResponseTransfer = $plugin->generate(static::CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT);

        $this->assertNotEmpty($barcodeResponseTransfer->getCode());
        $this->assertNotEmpty($barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testCode128BarcodeGeneratorPluginBarcodeResponseTransferHasCorrectData(): void
    {
        $plugin = new Code128BarcodeGeneratorPlugin();
        $barcodeResponseTransfer = $plugin->generate(static::CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT);

        $this->assertSame(static::CODE128_BARCODE_GENERATOR_PLUGIN_GENERATED_TEXT, $barcodeResponseTransfer->getCode());
        $this->assertSame(static::CODE128_BARCODE_GENERATOR_PLUGIN_ENCODING, $barcodeResponseTransfer->getEncoding());
    }
}

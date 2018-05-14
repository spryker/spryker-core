<?php

namespace SprykerTest\Service\CodeItNow\Plugin;

use Codeception\Test\Unit;
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

    /**
     * @return void
     */
    public function testCode128BarcodeGeneration(): void
    {
        $plugin = new Code128BarcodeGeneratorPlugin();
        $plugin->generate(static::CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT);
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

        $this->assertNotEmpty($barcodeResponseTransfer->getCode());
        $this->assertSame(static::CODE128_BARCODE_GENERATOR_PLUGIN_ENCODING, $barcodeResponseTransfer->getEncoding());
    }
}

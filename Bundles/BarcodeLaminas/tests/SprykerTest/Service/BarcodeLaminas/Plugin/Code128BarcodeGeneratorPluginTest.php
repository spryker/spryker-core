<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\BarcodeLaminas\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\BarcodeLaminas\Plugin\Code128BarcodeGeneratorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group BarcodeLaminas
 * @group Plugin
 * @group Code128BarcodeGeneratorPluginTest
 * Add your own group annotations below this line
 *
 * @group Barcode
 */
class Code128BarcodeGeneratorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Service\BarcodeLaminas\Plugin\Code128BarcodeGeneratorPlugin::ENCODING
     *
     * @var string
     */
    protected const CODE128_BARCODE_GENERATOR_PLUGIN_ENCODING = 'data:image/png;base64';

    /**
     * @var string
     */
    protected const CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT = 'generated text';

    /**
     * @return void
     */
    public function testCode128BarcodeGeneratorPluginBarcodeResponseTransferHasCorrectData(): void
    {
        $code128BarcodeGeneratorPlugin = new Code128BarcodeGeneratorPlugin();
        $barcodeResponseTransfer = $code128BarcodeGeneratorPlugin->generate(static::CODE128_BARCODE_GENERATOR_PLUGIN_SOURCE_TEXT);

        $this->assertNotEmpty($barcodeResponseTransfer->getCode());
        $this->assertSame(static::CODE128_BARCODE_GENERATOR_PLUGIN_ENCODING, $barcodeResponseTransfer->getEncoding());
    }
}

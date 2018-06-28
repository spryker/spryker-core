<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Barcode;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\BarcodeDependencyProvider;
use Spryker\Service\Barcode\Exception\BarcodeGeneratorPluginNotFoundException;
use SprykerTest\Service\Barcode\Mocks\BarcodeGeneratorPluginMockRegistered1;
use SprykerTest\Service\Barcode\Mocks\BarcodeGeneratorPluginMockRegistered2;
use SprykerTest\Service\Barcode\Mocks\BarcodeGeneratorPluginMockUnregistered;

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
    protected const GENERATED_CODE = 'generated string';
    protected const GENERATED_ENCODING = 'data:image/png;base64';

    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    protected $registeredBarcodeGeneratorPlugins;

    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected $activeBarcodeGeneratorPlugin;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->registeredBarcodeGeneratorPlugins = [
            new BarcodeGeneratorPluginMockRegistered1(),
            new BarcodeGeneratorPluginMockRegistered2(),
        ];

        $this->activeBarcodeGeneratorPlugin = reset($this->registeredBarcodeGeneratorPlugins);

        $this->tester->setDependency(BarcodeDependencyProvider::PLUGINS_BARCODE_GENERATOR, $this->registeredBarcodeGeneratorPlugins);
    }

    /**
     * @return void
     */
    public function testGenerateBarcode(): void
    {
        $barcodeResponseTransfer = $this->tester->generateBarcodeUsingBarcodeService(
            get_class($this->activeBarcodeGeneratorPlugin)
        );

        $this->assertSame(BarcodeGeneratorPluginMockRegistered1::GENERATED_CODE, $barcodeResponseTransfer->getCode());
        $this->assertSame(BarcodeGeneratorPluginMockRegistered1::GENERATED_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testGeneratedBarcodeResponseHasCorrectType(): void
    {
        $barcodeResponseTransfer = $this
            ->tester
            ->generateBarcodeUsingBarcodeService(
                get_class($this->activeBarcodeGeneratorPlugin)
            );

        $this->assertInstanceOf(BarcodeResponseTransfer::class, $barcodeResponseTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratedBarcodeResponseIsNotEmpty(): void
    {
        $barcodeResponseTransfer = $this->tester->generateBarcodeUsingBarcodeService(
            get_class($this->activeBarcodeGeneratorPlugin)
        );

        $this->assertNotEmpty($barcodeResponseTransfer->getCode());
        $this->assertNotEmpty($barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testBarcodeGeneratorUsesPluginFullQualifiedClassName(): void
    {
        $barcodeGeneratorPluginClass = get_class($this->activeBarcodeGeneratorPlugin);

        $this->assertTrue(class_exists($barcodeGeneratorPluginClass));
    }

    /**
     * @return void
     */
    public function testBarcodeGeneratorUsesFirstRegisteredPlugin(): void
    {
        $firstRegisteredPlugin = reset($this->registeredBarcodeGeneratorPlugins);

        $barcodeResponseTransfer = $this
            ->tester
            ->generateBarcodeUsingBarcodeService();

        $this->assertSame($firstRegisteredPlugin::GENERATED_CODE, $barcodeResponseTransfer->getCode());
        $this->assertSame($firstRegisteredPlugin::GENERATED_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testBarcodeGeneratorThrowsExceptionForUnregisteredPlugin(): void
    {
        $unregisteredPlugin = new BarcodeGeneratorPluginMockUnregistered();

        $this->tester->expectException(BarcodeGeneratorPluginNotFoundException::class, function () use ($unregisteredPlugin) {
            $this->tester->generateBarcodeUsingBarcodeService(get_class($unregisteredPlugin));
        });
    }
}

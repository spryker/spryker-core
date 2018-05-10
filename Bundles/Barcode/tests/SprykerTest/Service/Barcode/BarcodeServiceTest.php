<?php

namespace SprykerTest\Service\Barcode;

use Codeception\TestCase\Test;
use Exception;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
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
    protected const GENERATED_CODE = 'generated string';
    protected const GENERATED_ENCODING = 'data:image/png;base64';

    protected const TEST_PLUGIN_0_ENCODING = 'test_plugin_0_encoding';
    protected const TEST_PLUGIN_1_ENCODING = 'test_plugin_1_encoding';

    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface[]
     */
    protected $registeredBarcodeGeneratorPlugins;

    /**
     * @var \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    protected $barcodeGeneratorPlugin;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->registeredBarcodeGeneratorPlugins = [
            $this->tester->getBarcodePluginMock(static::TEST_PLUGIN_0_ENCODING),
            $this->tester->getBarcodePluginMockUsingAlphaAnonymousClass(static::TEST_PLUGIN_1_ENCODING),
            $this->tester->getBarcodePluginMockUsingBetaAnonymousClass(static::TEST_PLUGIN_1_ENCODING),
        ];

        $this->barcodeGeneratorPlugin = reset($this->registeredBarcodeGeneratorPlugins);

        $this->tester->setDependency(BarcodeDependencyProvider::PLUGINS_BARCODE_GENERATOR, $this->registeredBarcodeGeneratorPlugins);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testGenerateBarcode(): void
    {

        // this works
//        throw new \Exception($this
//            ->tester
//            ->getBarcodePluginMock()
//            ->generate('text')->getEncoding());


        // this doesn't
//        throw new \Exception($this
//            ->generateBarcode(get_class(
//                $this->tester->getBarcodePluginMock()
//            ))->getEncoding());


        // what about real one?
//        throw new \Exception($this->generateBarcode(Code128BarcodeGeneratorPlugin::class)->getEncoding());


        $test = [
            'registered' => [],
            'newMock' => get_class($this->tester->getBarcodePluginMock()),
        ];

        foreach ($this->registeredBarcodeGeneratorPlugins as $plugin) {
            $test['registered'][] = get_class($plugin);
        }

        $test['in_array'] = in_array($test['newMock'], $test['registered']);

        throw new Exception(json_encode($test, JSON_PRETTY_PRINT));

//        $barcodeGeneratorPluginWithDefaultEncoding = get_class($this->tester->getBarcodePluginMock());
//        $barcodeResponseTransfer = $this->generateBarcode($barcodeGeneratorPluginWithDefaultEncoding);
//
//        $this->assertSame(static::GENERATED_CODE, $barcodeResponseTransfer->getCode());
//        $this->assertSame(static::GENERATED_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testGeneratedBarcodeResponseHasCorrectType(): void
    {
        $barcodeResponseTransfer = $this->generateBarcode();

        $this->assertInstanceOf(BarcodeResponseTransfer::class, $barcodeResponseTransfer);
    }

    /**
     * @return void
     */
    public function testGeneratedBarcodeResponseIsNotEmpty(): void
    {
        $barcodeResponseTransfer = $this->generateBarcode();

        $this->assertNotEmpty($barcodeResponseTransfer->getCode());
        $this->assertNotEmpty($barcodeResponseTransfer->getEncoding());
    }

    /**
     * @return void
     */
    public function testBarcodeGeneratorUsesPluginFullQualifiedClassName(): void
    {
        $barcodeGeneratorPluginClass = get_class($this->barcodeGeneratorPlugin);

        $this->assertTrue(class_exists($barcodeGeneratorPluginClass));
    }

    /**
     * @return void
     */
    public function testBarcodeGeneratorUsesRegisteredPlugin(): void
    {
        $barcodeResponseTransfer = $this->generateBarcode();

        $this->assertSame(static::TEST_PLUGIN_0_ENCODING, $barcodeResponseTransfer->getEncoding());
    }

    /**
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function generateBarcode(?string $generatorPlugin = null): BarcodeResponseTransfer
    {
        $generatorPluginClass = $generatorPlugin ?: get_class($this->barcodeGeneratorPlugin);

        return $this
            ->tester
            ->getBarcodeService()
            ->generateBarcode(
                static::GENERATED_CODE,
                $generatorPluginClass
            );
    }
}

<?php

namespace SprykerTest\Service\Barcode;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\BarcodeDependencyProvider;
use Spryker\Service\Barcode\BarcodeServiceInterface;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

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
        $barcodeResponseTransfer = $this->getService()
            ->generateBarcode(static::GENERATION_SEED, get_class($this->barcodePlugin));

        $this->assertSame(static::GENERATED_CODE, $barcodeResponseTransfer->getCode());
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

        $barcodePlugin
            ->expects($this->once())
            ->method('generate')
            ->willReturn($barcodeTransfer);

        return $barcodePlugin;
    }

    /**
     * @return \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    protected function getService(): BarcodeServiceInterface
    {
        return $this->tester
            ->getLocator()
            ->barcode()
            ->service();
    }
}

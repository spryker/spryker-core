<?php

namespace SprykerTest\Service\Barcode\Helper;

use Codeception\Module;
use Codeception\Stub\Expected;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class BarcodeMockHelper extends Module
{
    protected const GENERATED_CODE = 'generated string';
    protected const GENERATED_ENCODING = 'data:image/png;base64';

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodePluginMock(): BarcodeGeneratorPluginInterface
    {
        $barcodePlugin = Stub::makeEmpty(BarcodeGeneratorPluginInterface::class, [
            'generate' => function (string $text): BarcodeResponseTransfer {
                return (new BarcodeResponseTransfer())
                    ->setCode(static::GENERATED_CODE)
                    ->setEncoding(static::GENERATED_ENCODING);
            },
        ]);
        $barcodeTransfer = $this->getBarcodeResponseTransfer();

        $barcodePlugin
            ->expects(Expected::once()->getMatcher())
            ->method('generate')
            ->willReturn($barcodeTransfer);

        return $barcodePlugin;
    }

    /**
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function getBarcodeResponseTransfer(): BarcodeResponseTransfer
    {
        $barcodeResponseTransfer = new BarcodeResponseTransfer();

        return $barcodeResponseTransfer
            ->setCode(static::GENERATED_CODE)
            ->setEncoding(static::GENERATED_ENCODING);
    }
}

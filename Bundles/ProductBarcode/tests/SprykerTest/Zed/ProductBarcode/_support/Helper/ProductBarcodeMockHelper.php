<?php

namespace SprykerTest\Zed\ProductBarcode\Helper;

use Codeception\Module;
use Codeception\Stub\Expected;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

class ProductBarcodeMockHelper extends Module
{
    protected const GENERATED_ENCODING = 'data:image/png;base64';

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodePluginMock(): BarcodeGeneratorPluginInterface
    {
        $barcodePlugin = Stub::makeEmpty(BarcodeGeneratorPluginInterface::class, [
            'generate' => function (string $text): BarcodeResponseTransfer {
                return (new BarcodeResponseTransfer())
                    ->setCode($text)
                    ->setEncoding(static::GENERATED_ENCODING);
            },
        ]);

        $barcodePlugin
            ->expects(Expected::once()->getMatcher())
            ->method('generate')
            ->willReturnCallback(
                function (string $code) {
                    return (new BarcodeResponseTransfer())
                        ->setCode($code)
                        ->setEncoding(static::GENERATED_ENCODING);
                }
            );

        return $barcodePlugin;
    }
}

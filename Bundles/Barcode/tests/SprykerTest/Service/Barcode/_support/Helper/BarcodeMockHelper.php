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
     * @param null|string $encoding
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodePluginMock(?string $encoding = null): BarcodeGeneratorPluginInterface
    {
        $responseEncoding = $encoding ?: static::GENERATED_ENCODING;

        $barcodePlugin = Stub::makeEmpty(BarcodeGeneratorPluginInterface::class, [
            'generate' => function (string $text) use ($responseEncoding): BarcodeResponseTransfer {
                return (new BarcodeResponseTransfer())
                    ->setCode($text)
                    ->setEncoding($responseEncoding);
            },
        ]);

        $barcodeTransfer = (new BarcodeResponseTransfer())
            ->setCode(static::GENERATED_CODE)
            ->setEncoding($responseEncoding);

        $barcodePlugin
            ->expects(Expected::once()->getMatcher())
            ->method('generate')
            ->willReturn($barcodeTransfer);

        return $barcodePlugin;
    }

    /**
     * @param null|string $encoding
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodePluginMockUsingDefaultAnonymousClass(?string $encoding = null): BarcodeGeneratorPluginInterface
    {
        $responseEncoding = $encoding ?: static::GENERATED_ENCODING;

        return new class($responseEncoding) implements BarcodeGeneratorPluginInterface {
            /**
             * @var string
             */
            protected $dummy = 'default';

            /**
             * @var string
             */
            protected $encoding;

            /**
             * @param string $encoding
             */
            public function __construct(string $encoding)
            {
                $this->encoding = $encoding;
            }

            /**
             * @param string $text
             *
             * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
             */
            public function generate(string $text): BarcodeResponseTransfer
            {
                return (new BarcodeResponseTransfer())
                    ->setCode($text)
                    ->setEncoding($this->encoding);
            }
        };
    }

    /**
     * @param null|string $encoding
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodePluginMockUsingAlphaAnonymousClass(?string $encoding = null): BarcodeGeneratorPluginInterface
    {
        $responseEncoding = $encoding ?: static::GENERATED_ENCODING;

        return new class($responseEncoding) implements BarcodeGeneratorPluginInterface {
            /**
             * @var string
             */
            protected $dummy = 'alpha';

            /**
             * @var string
             */
            protected $encoding;

            /**
             * @param string $encoding
             */
            public function __construct(string $encoding)
            {
                $this->encoding = $encoding;
            }

            /**
             * @param string $text
             *
             * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
             */
            public function generate(string $text): BarcodeResponseTransfer
            {
                return (new BarcodeResponseTransfer())
                    ->setCode($text)
                    ->setEncoding($this->encoding);
            }
        };
    }

    /**
     * @param null|string $encoding
     *
     * @return \Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface
     */
    public function getBarcodePluginMockUsingBetaAnonymousClass(?string $encoding = null): BarcodeGeneratorPluginInterface
    {
        $responseEncoding = $encoding ?: static::GENERATED_ENCODING;

        return new class($responseEncoding) implements BarcodeGeneratorPluginInterface {
            /**
             * @var string
             */
            protected $dummy = 'beta';

            /**
             * @var string
             */
            protected $encoding;

            /**
             * @param string $encoding
             */
            public function __construct(string $encoding)
            {
                $this->encoding = $encoding;
            }

            /**
             * @param string $text
             *
             * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
             */
            public function generate(string $text): BarcodeResponseTransfer
            {
                return (new BarcodeResponseTransfer())
                    ->setCode($text)
                    ->setEncoding($this->encoding);
            }
        };
    }
}

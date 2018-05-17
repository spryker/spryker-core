<?php

namespace SprykerTest\Service\Barcode\Mocks;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;

abstract class AbstractBarcodeGeneratorPluginMock implements BarcodeGeneratorPluginInterface
{
    public const GENERATED_CODE = 'abstract_mock_generated_code';
    public const GENERATED_ENCODING = 'abstract_mock_generated_encoding';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        return (new BarcodeResponseTransfer())
            ->setCode(static::GENERATED_CODE)
            ->setEncoding(static::GENERATED_ENCODING);
    }
}

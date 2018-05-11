<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow\Plugin;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

class Code128BarcodeGeneratorPlugin extends AbstractPlugin implements BarcodeGeneratorPluginInterface
{
    public const ENCODING = 'data:image/png;base64';

    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        $code = $this
            ->createBarcodeGenerator($text)
            ->generate();

        return $this->createBarcodeResponseTransfer($code);
    }

    /**
     * @param string $text
     *
     * @return \CodeItNow\BarcodeBundle\Utils\BarcodeGenerator
     */
    protected function createBarcodeGenerator(string $text): BarcodeGenerator
    {
        $barcodeGenerator = new BarcodeGenerator();

        $barcodeGenerator->setText($text);
        $barcodeGenerator->setType(BarcodeGenerator::Code128);

        return $barcodeGenerator;
    }

    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function createBarcodeResponseTransfer(string $text): BarcodeResponseTransfer
    {
        return (new BarcodeResponseTransfer())
            ->setCode($text)
            ->setEncoding(static::ENCODING);
    }
}

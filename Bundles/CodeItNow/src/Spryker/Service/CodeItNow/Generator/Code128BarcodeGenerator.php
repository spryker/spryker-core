<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow\Generator;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Generated\Shared\Transfer\BarcodeResponseTransfer;

class Code128BarcodeGenerator implements BarcodeGeneratorInterface
{
    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        $barcodeResponseTransfer = new BarcodeResponseTransfer();
        $barcode = new BarcodeGenerator();
        $barcode->setText($text);
        $barcode->setType(BarcodeGenerator::Code128);
        $code = $barcode->generate();

        $barcodeResponseTransfer
            ->setCode($code)
            ->setEncoding(static::ENCODING);

        return $barcodeResponseTransfer;
    }
}

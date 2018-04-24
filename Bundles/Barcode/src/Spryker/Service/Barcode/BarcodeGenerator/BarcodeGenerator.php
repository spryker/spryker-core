<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\BarcodeGenerator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

class BarcodeGenerator implements BarcodeGeneratorInterface
{
    /**
     * @param string $text
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(string $text, string $generatorPlugin = null): BarcodeResponseTransfer
    {
        // TODO: Implement generateBarcode() method.
    }
}

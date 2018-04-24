<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\BarcodeGenerator\BarcodeGenerator;
use Spryker\Service\Kernel\AbstractServiceFactory;

class BarcodeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Barcode\BarcodeGenerator\BarcodeGeneratorInterface
     */
    public function createBarcodeGenerator(): BarcodeGenerator
    {
        return new BarcodeGenerator();
    }

    /**
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function createBarcodeTransferObject(): BarcodeResponseTransfer
    {
        return new BarcodeResponseTransfer();
    }
}

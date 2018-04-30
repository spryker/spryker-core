<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Barcode\BarcodeServiceFactory getFactory()
 */
class BarcodeService extends AbstractService implements BarcodeServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $text
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(string $text, string $generatorPlugin = null): BarcodeResponseTransfer
    {
        return $this->getFactory()
            ->createBarcodeGenerator()
            ->generateBarcode($text, $generatorPlugin);
    }
}

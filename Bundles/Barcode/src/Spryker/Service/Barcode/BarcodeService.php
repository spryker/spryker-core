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
     * Specification:
     * - Generates a barcode based on the given $text.
     * - Returns a base64 encoded string that represents the barcode.
     * - The generation is based on the given $generatorPlugin which is the FQCN of a registered barcode generator plugin.
     * - When the plugin is not provided, uses the first registered plugin.
     * - Throws exception when plugin not found.
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

interface ProductBarcodeClientInterface
{
    /**
     * Specification:
     * - Generates a barcode for the given concrete product with the use of Barcode module service.
     * - Barcode generated using SKU as data.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $barcodeGeneratorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcodeBySku(
        string $sku,
        ?string $barcodeGeneratorPlugin = null
    ): BarcodeResponseTransfer;
}

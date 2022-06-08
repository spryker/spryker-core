<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeLaminas;

use Generated\Shared\Transfer\BarcodeRequestTransfer;
use Generated\Shared\Transfer\BarcodeResponseTransfer;

interface BarcodeLaminasServiceInterface
{
    /**
     * Specification:
     * - Requiers `BarcodeRequestTransfer.text` to be set.
     * - Requires `BarcodeRequestTransfer.barcodeType` to be set to a value supported by Laminas barcode library (e.g 'code128').
     * - Requires `BarcodeRequestTransfer.renderer` to be set to a value supported by Laminas barcode library (e.g 'image').
     * - Generates a barcode based on the given text.
     * - Returns BarcodeResponseTransfer with encoding (like base64) and encoded string that represents the barcode.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\BarcodeRequestTransfer $barcodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(BarcodeRequestTransfer $barcodeRequestTransfer): BarcodeResponseTransfer;
}

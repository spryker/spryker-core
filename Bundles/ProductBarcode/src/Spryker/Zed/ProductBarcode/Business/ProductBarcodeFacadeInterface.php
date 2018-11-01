<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBarcodeFacadeInterface
{
    /**
     * Specification:
     * - Generates a barcode for the given concrete product with the use of Barcode module service.
     * - Barcode generated using SKU as data.
     * - If ProductConcreteTransfer has no SKU, it will be obtained from DB using idProductConcrete.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(
        ProductConcreteTransfer $productConcreteTransfer,
        ?string $generatorPlugin = null
    ): BarcodeResponseTransfer;
}

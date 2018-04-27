<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBarcodeGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, ?string $generatorPlugin): BarcodeResponseTransfer;
}

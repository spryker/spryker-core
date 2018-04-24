<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductBarcodeNumberResolver;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBarcodeNumberResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function resolve(ProductConcreteTransfer $productConcreteTransfer): string;
}

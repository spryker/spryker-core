<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductStockCodeSelector;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductStockCodeSelectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function selectAppropriateCode(ProductConcreteTransfer $productConcreteTransfer): string;
}

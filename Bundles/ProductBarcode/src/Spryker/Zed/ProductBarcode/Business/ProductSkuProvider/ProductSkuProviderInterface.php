<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductSkuProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductSkuProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function getConcreteProductSku(ProductConcreteTransfer $productConcreteTransfer): string;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface PriceProductFilterExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands PriceProductFilterTransfer with additional parameters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer, PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductFilterTransfer;
}

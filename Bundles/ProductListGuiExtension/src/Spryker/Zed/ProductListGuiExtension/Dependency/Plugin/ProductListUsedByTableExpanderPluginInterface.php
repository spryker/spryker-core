<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductListUsedByTableTransfer;

interface ProductListUsedByTableExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands Product List Edit page Used By tab table with data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function expand(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer;
}

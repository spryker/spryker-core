<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListTypeExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductListTransfer $productListTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expand(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer;
}

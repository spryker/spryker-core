<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductListResponseTransfer;

interface ProductListPreSaverInterface
{
    /**
     * Specification:
     * - Executes plugins before a product list is saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function preSave(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer;
}

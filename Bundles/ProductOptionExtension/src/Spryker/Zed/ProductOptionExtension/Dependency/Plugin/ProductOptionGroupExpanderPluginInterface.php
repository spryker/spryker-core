<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

/**
 * Allows to expand ProductOptionGroupTransfer.
 */
interface ProductOptionGroupExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands a product options group data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function expand(ProductOptionGroupTransfer $productOptionGroupTransfer): ProductOptionGroupTransfer;
}

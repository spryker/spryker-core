<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface AlternativeProductApplicablePluginInterface
{
    /**
     * Specification:
     *  - Checks if product alternatives should be shown for product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function check(ProductViewTransfer $productViewTransfer): bool;
}

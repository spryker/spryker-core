<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewExpanderPluginInterface
{
    /**
     * Specification:
     * - TODO: fix specification
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $locale);
}

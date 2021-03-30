<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Filter;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductAttributeFilterInterface
{
    /**
     * @param array $selectedVariantNode
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    public function filterAvailableProductAttributes(
        array $selectedVariantNode,
        ProductViewTransfer $productViewTransfer
    ): array;
}

<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewPriceExpanderInterface
{
    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return ProductViewTransfer
     */
    public function expandProductViewPriceData(ProductViewTransfer $productViewTransfer);
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander;

use Generated\Shared\Transfer\ProductViewTransfer;

interface DiscontinuedOptionsProductViewExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandDiscontinuedProductOptions(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer;
}

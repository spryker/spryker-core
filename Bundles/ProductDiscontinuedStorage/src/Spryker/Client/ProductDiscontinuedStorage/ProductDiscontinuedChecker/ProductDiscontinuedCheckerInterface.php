<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedChecker;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductDiscontinuedCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return bool
     */
    public function isProductDiscontinued(ProductViewTransfer $productViewTransfer, string $localeName): bool;
}

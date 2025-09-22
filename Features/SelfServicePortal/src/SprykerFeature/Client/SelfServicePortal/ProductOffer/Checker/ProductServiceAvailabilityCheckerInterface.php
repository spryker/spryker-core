<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\ProductOffer\Checker;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductServiceAvailabilityCheckerInterface
{
    public function isApplicable(ProductViewTransfer $productViewTransfer): bool;

    public function isProductAvailable(ProductViewTransfer $productViewTransfer): bool;
}

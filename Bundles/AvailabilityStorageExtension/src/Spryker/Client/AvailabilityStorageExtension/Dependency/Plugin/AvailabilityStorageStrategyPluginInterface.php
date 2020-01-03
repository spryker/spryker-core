<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface AvailabilityStorageStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy needs to be applied.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductViewTransfer $productViewTransfer): bool;

    /**
     * Specification:
     * - Returns true if product available for provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductAvailable(ProductViewTransfer $productViewTransfer): bool;
}

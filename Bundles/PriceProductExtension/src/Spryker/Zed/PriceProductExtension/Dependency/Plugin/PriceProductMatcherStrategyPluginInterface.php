<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductMatcherStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy is applicable for provided parameters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(PriceProductTransfer $priceProductTransfer, ItemTransfer $itemTransfer): bool;

    /**
     * Specification:
     * - Returns true price belongs to provided product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isPriceProductMatchingItem(PriceProductTransfer $priceProductTransfer, ItemTransfer $itemTransfer): bool;
}

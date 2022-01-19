<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantityExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;

/**
 * Allows filtering out items before `isQuantitySplittable` expansion.
 */
interface NonSplittableItemFilterPluginInterface
{
    /**
     * Specification:
     * - Filters out item transfers that should not be expanded with `ItemTransfer.isQuantitySplittable` flag.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterNonSplittableItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}

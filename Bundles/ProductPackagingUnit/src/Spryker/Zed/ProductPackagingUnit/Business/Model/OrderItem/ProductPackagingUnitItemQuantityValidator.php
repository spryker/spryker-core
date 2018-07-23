<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;

class ProductPackagingUnitItemQuantityValidator implements ProductPackagingUnitItemQuantityValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isProductPackagingUnitItemQuantitySplittable(ItemTransfer $itemTransfer): bool
    {
        if ($this->isBundledItem($itemTransfer)) {
            return true;
        }

        return !$this->isNonSplittableItem($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBundledItem(ItemTransfer $itemTransfer): bool
    {
        if ($itemTransfer->getRelatedBundleItemIdentifier() || $itemTransfer->getBundleItemIdentifier()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isNonSplittableItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getIsQuantitySplittable() === false;
    }
}

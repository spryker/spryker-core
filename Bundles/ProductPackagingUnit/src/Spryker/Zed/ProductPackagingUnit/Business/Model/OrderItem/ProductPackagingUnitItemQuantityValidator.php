<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface;

class ProductPackagingUnitItemQuantityValidator implements ProductPackagingUnitItemQuantityValidatorInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface
     */
    protected $salesQuantityFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface $salesQuantityFacade
     */
    public function __construct(ProductPackagingUnitToSalesQuantityFacadeInterface $salesQuantityFacade)
    {
        $this->salesQuantityFacade = $salesQuantityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isProductPackagingUnitItemQuantitySplittable(ItemTransfer $itemTransfer): bool
    {
        // Packaging unit module does not handle bundled items
        if ($this->isBundledItem($itemTransfer)) {
            return false;
        }

        if (!$this->isPackagingUnit($itemTransfer)) {
            return false;
        }

        if (!$this->isSplittableQuantityThresholdExceeded($itemTransfer)) {
            return false;
        }

        return $this->isSplittableItem($itemTransfer);
    }

    /**
     * @uses ItemTransfer::getBundleItemIdentifier()
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBundledItem(ItemTransfer $itemTransfer): bool
    {
        if (!method_exists($itemTransfer, 'getBundleItemIdentifier')) {
            return false;
        }

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
    protected function isSplittableItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getIsQuantitySplittable() ?? true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isPackagingUnit(ItemTransfer $itemTransfer): bool
    {
        return !empty($itemTransfer->getAmountSalesUnit());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isSplittableQuantityThresholdExceeded(ItemTransfer $itemTransfer)
    {
        return $this->salesQuantityFacade->isItemQuantitySplittable($itemTransfer);
    }
}

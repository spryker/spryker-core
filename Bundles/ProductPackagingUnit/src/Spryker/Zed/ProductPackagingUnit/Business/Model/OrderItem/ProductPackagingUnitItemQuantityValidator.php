<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;

class ProductPackagingUnitItemQuantityValidator implements ProductPackagingUnitItemQuantityValidatorInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig $config
     */
    public function __construct(ProductPackagingUnitConfig $config)
    {
        $this->config = $config;
    }

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

        if ($this->isNonPackagingUnit($itemTransfer)) {
            return false;
        }

        if ($this->isNonSplittableQuantityThresholdExceeded($itemTransfer)) {
            return false;
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

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isNonPackagingUnit(ItemTransfer $itemTransfer): bool
    {
        return empty($itemTransfer->getAmountSalesUnit());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isNonSplittableQuantityThresholdExceeded(ItemTransfer $itemTransfer)
    {
        $threshold = $this->config->findItemQuantityThreshold();

        if ($threshold === null) {
            return false;
        }

        if ($itemTransfer->getQuantity() >= $threshold) {
            return true;
        }

        return false;
    }
}

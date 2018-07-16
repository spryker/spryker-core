<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Order\Item;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesQuantity\SalesQuantityConfig;

class ItemQuantityValidator implements ItemQuantityValidatorInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\SalesQuantityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\SalesQuantity\SalesQuantityConfig $config
     */
    public function __construct(SalesQuantityConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isItemQuantitySplitRequired(ItemTransfer $itemTransfer): bool
    {
        if ($this->isBundledItem($itemTransfer)) {
            return false;
        }

        if ($this->isNonSplittableItem($itemTransfer)) {
            return true;
        }

        if ($this->isQuantityThresholdExceeded($itemTransfer)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBundledItem(ItemTransfer $itemTransfer)
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
    protected function isNonSplittableItem(ItemTransfer $itemTransfer)
    {
        return $itemTransfer->getIsQuantitySplittable() === false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isQuantityThresholdExceeded(ItemTransfer $itemTransfer)
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

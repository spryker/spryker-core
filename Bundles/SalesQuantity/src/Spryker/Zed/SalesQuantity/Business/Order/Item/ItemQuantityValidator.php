<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Order\Item;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilQuantityServiceInterface;
use Spryker\Zed\SalesQuantity\SalesQuantityConfig;

class ItemQuantityValidator implements ItemQuantityValidatorInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\SalesQuantityConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\SalesQuantity\SalesQuantityConfig $config
     * @param \Spryker\Zed\SalesQuantity\Dependency\Service\SalesQuantityToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        SalesQuantityConfig $config,
        SalesQuantityToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->config = $config;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isItemQuantitySplittable(ItemTransfer $itemTransfer): bool
    {
        if ($this->isBundledItem($itemTransfer)) {
            return true;
        }

        if ($this->isNonSplittableItem($itemTransfer)) {
            return false;
        }

        if ($this->isNonSplittableQuantityThresholdExceeded($itemTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @uses ItemTransfer::getBundleItemIdentifier()
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBundledItem(ItemTransfer $itemTransfer)
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
    protected function isNonSplittableItem(ItemTransfer $itemTransfer)
    {
        return $itemTransfer->getIsQuantitySplittable() === false;
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

        if ($this->isQuantityGreaterOrEqual($itemTransfer->getQuantity(), $threshold)) {
            return true;
        }

        return false;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityGreaterOrEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityGreaterOrEqual($firstQuantity, $secondQuantity);
    }
}

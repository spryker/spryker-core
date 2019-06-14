<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface;

class QuoteItemFinder implements QuoteItemFinderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(ProductBundleToUtilQuantityServiceInterface $utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findItem(QuoteTransfer $quoteTransfer, string $sku, ?string $groupKey = null): ?ItemTransfer
    {
        $itemTransfer = null;
        if ($groupKey) {
            $itemTransfer = $this->findBundleItem($quoteTransfer, $groupKey);
        }
        if (!$itemTransfer) {
            $itemTransfer = $this->findQuoteItem($quoteTransfer, $sku, $groupKey);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findBundleItem(QuoteTransfer $quoteTransfer, $groupKey): ?ItemTransfer
    {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if ($itemTransfer->getGroupKey() === $groupKey) {
                $itemTransfer = clone $itemTransfer;
                $itemTransfer->setQuantity($this->getBundledProductTotalQuantity($quoteTransfer, $groupKey));

                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     *
     * @return float
     */
    protected function getBundledProductTotalQuantity(QuoteTransfer $quoteTransfer, string $groupKey): float
    {
        $bundleItemQuantity = 0.0;
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            $bundleItemQuantity = $this->sumQuantities(
                $bundleItemQuantity,
                $bundleItemTransfer->getQuantity()
            );
        }

        return $bundleItemQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findQuoteItem(QuoteTransfer $quoteTransfer, string $sku, ?string $groupKey = null): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (($itemTransfer->getSku() === $sku && $groupKey === null) ||
                $itemTransfer->getGroupKey() === $groupKey) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\QuoteChangeRequestExpander;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface;

class QuoteChangeRequestExpander implements QuoteChangeRequestExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Client\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(ProductBundleToUtilQuantityServiceInterface $utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        $itemTransferList = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $bundledItemTransferList = $this->getBundledItems(
                $cartChangeTransfer->getQuote(),
                $itemTransfer->getGroupKey(),
                $itemTransfer->getQuantity()
            );

            if (count($bundledItemTransferList)) {
                $itemTransferList = array_merge($itemTransferList, $bundledItemTransferList);

                continue;
            }

            $itemTransferList[] = $itemTransfer;
        }

        $cartChangeTransfer->setItems(new ArrayObject($itemTransferList));

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     * @param float $numberOfBundlesToRemove
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getBundledItems(QuoteTransfer $quoteTransfer, $groupKey, $numberOfBundlesToRemove): array
    {
        if (!$numberOfBundlesToRemove) {
            $numberOfBundlesToRemove = $this->getBundledProductTotalQuantity($quoteTransfer, $groupKey);
        }

        $numberOfBundlesToRemove = (int)$numberOfBundlesToRemove;
        $bundledItems = [];
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($numberOfBundlesToRemove === 0) {
                return $bundledItems;
            }

            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getRelatedBundleItemIdentifier() !== $bundleItemTransfer->getBundleItemIdentifier()) {
                    continue;
                }

                $bundledItems[] = $itemTransfer;
            }

            $numberOfBundlesToRemove--;
        }

        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     *
     * @return float
     */
    protected function getBundledProductTotalQuantity(QuoteTransfer $quoteTransfer, $groupKey): float
    {
        $bundleItemQuantity = 0.0;
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            $bundleItemQuantity += $bundleItemTransfer->getQuantity();
        }

        return $this->roundQuantity($bundleItemQuantity);
    }

    /**
     * @param float $quantity
     *
     * @return float
     */
    protected function roundQuantity(float $quantity): float
    {
        return $this->utilQuantityService->roundQuantity($quantity);
    }
}

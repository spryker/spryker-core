<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductBundleGrouper implements ProductBundleGrouperInterface
{
    const BUNDLE_ITEMS = 'bundleItems';
    const BUNDLE_PRODUCT = 'bundleProduct';
    protected const GROUP_KEY_FORMAT = '%s_%s';

    /**
     * @var array
     */
    protected $bundleGroupKeys = [];

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsWithBundlesItems(QuoteTransfer $quoteTransfer): array
    {
        $items = $this->getGroupedBundleItems($quoteTransfer->getItems(), $quoteTransfer->getBundleItems());
        $items = array_map(function ($groupedItem) {
            if ($groupedItem instanceof ItemTransfer) {
                return $groupedItem;
            }

            return $groupedItem[static::BUNDLE_PRODUCT];
        }, $items);

        return $items;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return array
     */
    public function getGroupedBundleItems(ArrayObject $items, ArrayObject $bundleItems)
    {
        $groupedBundleQuantity = $this->getGroupedBundleQuantity($bundleItems, $items);

        $singleItems = [];
        $groupedBundleItems = [];
        foreach ($items as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                $singleItems[] = $itemTransfer;
            }

            foreach ($bundleItems as $bundleItemTransfer) {
                if ($bundleItemTransfer->getBundleItemIdentifier() !== $itemTransfer->getRelatedBundleItemIdentifier()) {
                    continue;
                }

                $bundleGroupKey = $this->getBundleItemGroupKey($bundleItemTransfer, $items);
                $groupedBundleItems = $this->getCurrentBundle(
                    $groupedBundleItems,
                    $bundleItemTransfer,
                    $groupedBundleQuantity,
                    $bundleGroupKey
                );

                $currentBundleItemTransfer = $this->getBundleProduct($groupedBundleItems, $bundleGroupKey);
                if ($currentBundleItemTransfer->getBundleItemIdentifier() !== $itemTransfer->getRelatedBundleItemIdentifier()) {
                    continue;
                }

                $groupedBundleItems[$bundleGroupKey][static::BUNDLE_ITEMS] = $this->groupBundledItems(
                    $groupedBundleItems,
                    $itemTransfer,
                    $bundleGroupKey
                );
            }
        }

        $groupedBundleItems = $this->updateGroupedBundleItemsAggregatedSubtotal($groupedBundleItems, $bundleItems);

        return array_merge(
            $singleItems,
            $groupedBundleItems
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string
     */
    protected function getBundleItemGroupKey(ItemTransfer $bundleItemTransfer, ArrayObject $items)
    {
        if (isset($this->bundleGroupKeys[$bundleItemTransfer->getBundleItemIdentifier()])) {
            return $this->bundleGroupKeys[$bundleItemTransfer->getBundleItemIdentifier()];
        }

        $bundleOptions = $this->getBundleOptions($bundleItemTransfer, $items);
        if (count($bundleOptions) == 0) {
            return $this->buildGroupKey($bundleItemTransfer);
        }

        $bundleOptions = $this->sortOptions($bundleOptions);
        $bundleItemTransfer->setProductOptions(new ArrayObject($bundleOptions));

        $this->bundleGroupKeys[$bundleItemTransfer->getBundleItemIdentifier()] = $this->buildGroupKey($bundleItemTransfer) . '_' . $this->combineOptionParts($bundleOptions);

        return $this->bundleGroupKeys[$bundleItemTransfer->getBundleItemIdentifier()];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $itemTransfer): string
    {
        if ($itemTransfer->getGroupKeyPrefix()) {
            return sprintf(static::GROUP_KEY_FORMAT, $itemTransfer->getGroupKeyPrefix(), $itemTransfer->getSku());
        }

        return $itemTransfer->getSku();
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function sortOptions(array $options)
    {
        usort(
            $options,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getSku() < $productOptionRight->getSku()) ? -1 : 1;
            }
        );

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $sortedProductOptions
     *
     * @return string
     */
    protected function combineOptionParts(array $sortedProductOptions)
    {
        $groupKeyPart = [];
        foreach ($sortedProductOptions as $productOptionTransfer) {
            if (!$productOptionTransfer->getSku()) {
                continue;
            }

            $groupKeyPart[] = $productOptionTransfer->getSku();
        }

        return implode('_', $groupKeyPart);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    protected function getGroupedBundleQuantity(ArrayObject $bundleItems, ArrayObject $items)
    {
        $groupedBundleQuantity = [];
        foreach ($bundleItems as $bundleItemTransfer) {
            $bundleGroupKey = $this->getBundleItemGroupKey($bundleItemTransfer, $items);
            if (!isset($groupedBundleQuantity[$bundleGroupKey])) {
                $groupedBundleQuantity[$bundleGroupKey] = $bundleItemTransfer->getQuantity();
            } else {
                $groupedBundleQuantity[$bundleGroupKey] += $bundleItemTransfer->getQuantity();
            }
        }
        return $groupedBundleQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param array $groupedBundleQuantity
     * @param string $bundleGroupKey
     *
     * @return array
     */
    protected function getCurrentBundle(
        array $bundleItems,
        ItemTransfer $bundleItemTransfer,
        array $groupedBundleQuantity,
        $bundleGroupKey
    ) {

        if (isset($bundleItems[$bundleGroupKey])) {
            return $bundleItems;
        }

        $bundleProduct = clone $bundleItemTransfer;

        $bundleProduct->setSumSubtotalAggregation(0);
        $bundleProduct->setUnitSubtotalAggregation(0);
        $bundleProduct->setQuantity($groupedBundleQuantity[$bundleGroupKey]);

        $bundleItems[$bundleGroupKey] = [
            static::BUNDLE_PRODUCT => $bundleProduct,
            static::BUNDLE_ITEMS => [],
        ];

        return $bundleItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     * @param \Generated\Shared\Transfer\ItemTransfer $bundledItemTransfer
     * @param string $bundleGroupKey
     *
     * @return array
     */
    protected function groupBundledItems(array $bundleItems, ItemTransfer $bundledItemTransfer, $bundleGroupKey)
    {
        $currentBundledItems = $this->getAlreadyBundledItems($bundleItems, $bundleGroupKey);
        $currentBundleIdentifer = $bundledItemTransfer->getSku() . $bundledItemTransfer->getRelatedBundleItemIdentifier();

        if (!isset($currentBundledItems[$currentBundleIdentifer])) {
            $currentBundledItems[$currentBundleIdentifer] = clone $bundledItemTransfer;
        } else {
            $currentBundleItemTransfer = $currentBundledItems[$currentBundleIdentifer];
            $currentBundleItemTransfer->setQuantity(
                $currentBundleItemTransfer->getQuantity() + $bundledItemTransfer->getQuantity()
            );
        }

        return $currentBundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     * @param string $bundleGroupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getBundleProduct(array $bundleItems, $bundleGroupKey)
    {
        return $bundleItems[$bundleGroupKey][static::BUNDLE_PRODUCT];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     * @param string $bundleGroupKey
     *
     * @return array
     */
    protected function getAlreadyBundledItems(array $bundleItems, $bundleGroupKey)
    {
        return $bundleItems[$bundleGroupKey][static::BUNDLE_ITEMS];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function getBundleOptions(ItemTransfer $itemTransfer, ArrayObject $items)
    {
        foreach ($items as $cartItemTransfer) {
            if ($itemTransfer->getBundleItemIdentifier() === $cartItemTransfer->getRelatedBundleItemIdentifier()
                && count($cartItemTransfer->getProductOptions()) > 0) {
                return (array)$cartItemTransfer->getProductOptions();
            }
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $groupedBundleItems
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function updateGroupedBundleItemsAggregatedSubtotal(array $groupedBundleItems, ArrayObject $bundleItems)
    {
        foreach ($groupedBundleItems as $groupedBundle) {
            $groupedBundleItemTransfer = $groupedBundle[static::BUNDLE_PRODUCT];

            foreach ($bundleItems as $bundleItemTransfer) {
                if ($groupedBundleItemTransfer->getGroupKey() !== $bundleItemTransfer->getGroupKey()) {
                    continue;
                }

                $groupedBundleItemTransfer->setUnitSubtotalAggregation(
                    $groupedBundleItemTransfer->getUnitSubtotalAggregation() + $bundleItemTransfer->getUnitSubtotalAggregation()
                );

                $groupedBundleItemTransfer->setSumSubtotalAggregation(
                    $groupedBundleItemTransfer->getSumSubtotalAggregation() + $bundleItemTransfer->getSumSubtotalAggregation()
                );
            }
        }

        return $groupedBundleItems;
    }
}

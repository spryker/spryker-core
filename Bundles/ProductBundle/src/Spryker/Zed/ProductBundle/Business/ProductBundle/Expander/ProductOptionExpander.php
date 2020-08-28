<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

class ProductOptionExpander implements ProductOptionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderProductBundlesWithProductOptions(OrderTransfer $orderTransfer): OrderTransfer
    {
        $expandedProductBundles = new ArrayObject();

        foreach ($orderTransfer->getBundleItems() as $bundleItem) {
            $bundleItem = $this->getExpandedBundleItemWithProductOptions($orderTransfer, $bundleItem);

            $bundleItem = $bundleItem->setProductOptions(
                new ArrayObject($this->sortBundleProductOptions($bundleItem->getProductOptions()->getArrayCopy()))
            );

            $expandedProductBundles->append($bundleItem);
        }

        $orderTransfer->setBundleItems($expandedProductBundles);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemProductBundlesWithProductOptions(array $itemTransfers): array
    {
        $mappedProductBundles = $this->extractMappedProductBundlesWithProductOptions($itemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productBundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();

            if (!$productBundleItemIdentifier || !isset($mappedProductBundles[$productBundleItemIdentifier])) {
                continue;
            }

            $itemTransfer->setProductBundle($mappedProductBundles[$productBundleItemIdentifier]);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function extractMappedProductBundlesWithProductOptions(array $itemTransfers): array
    {
        $mappedProductBundles = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productBundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();

            if (!$productBundleItemIdentifier || !$itemTransfer->getProductBundle()) {
                continue;
            }

            $mappedProductBundles[$productBundleItemIdentifier] = $this->expandBundleItemWithProductOptions(
                $mappedProductBundles[$productBundleItemIdentifier] ?? $itemTransfer->getProductBundle(),
                $itemTransfer
            );
        }

        return $this->sortProductBundlesProductOptions($mappedProductBundles);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $productBundles
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function sortProductBundlesProductOptions(array $productBundles): array
    {
        foreach ($productBundles as $productBundle) {
            $productBundle->setProductOptions(
                new ArrayObject($this->sortBundleProductOptions($productBundle->getProductOptions()->getArrayCopy()))
            );
        }

        return $productBundles;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getExpandedBundleItemWithProductOptions(OrderTransfer $orderTransfer, ItemTransfer $bundleItem): ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() === $bundleItem->getBundleItemIdentifier()) {
                $bundleItem = $this->expandBundleItemWithProductOptions($bundleItem, $itemTransfer);
            }
        }

        return $bundleItem;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItem
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandBundleItemWithProductOptions(ItemTransfer $bundleItem, ItemTransfer $itemTransfer): ItemTransfer
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $bundleItem = $this->addProductOptionToBundleItem($bundleItem, $productOptionTransfer);
        }

        return $bundleItem;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItem
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addProductOptionToBundleItem(ItemTransfer $bundleItem, ProductOptionTransfer $productOptionTransfer): ItemTransfer
    {
        if (!$bundleItem->getProductOptions()->count()) {
            $bundleItem->getProductOptions()->append($productOptionTransfer);

            return $bundleItem;
        }

        foreach ($bundleItem->getProductOptions() as $productOption) {
            if ($productOption->getSku() !== $productOptionTransfer->getSku()) {
                $bundleItem->getProductOptions()->append($productOptionTransfer);

                break;
            }
        }

        return $bundleItem;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function sortBundleProductOptions(array $productOptionTransfers): array
    {
        usort(
            $productOptionTransfers,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getSku() < $productOptionRight->getSku()) ? -1 : 1;
            }
        );

        return $productOptionTransfers;
    }
}

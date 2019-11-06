<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Transformer;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig;

class ConfigurableBundleItemTransformer implements ConfigurableBundleItemTransformerInterface
{
    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig
     */
    protected $salesConfigurableBundleConfig;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig $salesConfigurableBundleConfig
     */
    public function __construct(
        SalesConfigurableBundleToSalesFacadeInterface $salesFacade,
        SalesConfigurableBundleConfig $salesConfigurableBundleConfig
    ) {
        $this->salesFacade = $salesFacade;
        $this->salesConfigurableBundleConfig = $salesConfigurableBundleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformConfigurableBundleItem(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
        $configuredBundleQuantity = (int)$itemTransfer->getConfiguredBundle()->getQuantity();
        if ($configuredBundleQuantity === 1) {
            return $this->transformItemTransferToItemCollectionTransfer($itemTransfer);
        }

        return $this->splitConfigurableBundleItems($itemTransfer, $configuredBundleQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $configuredBundleQuantity
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function splitConfigurableBundleItems(ItemTransfer $itemTransfer, int $configuredBundleQuantity): ItemCollectionTransfer
    {
        $configurableBundleItemCollectionTransfer = new ItemCollectionTransfer();

        for ($index = 1; $index <= $configuredBundleQuantity; $index++) {
            $transformedItemTransfer = $this->transformItemTransfer($itemTransfer, $index);
            $itemCollectionTransfer = $this->transformItemTransferToItemCollectionTransfer($transformedItemTransfer);

            foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
                $configurableBundleItemCollectionTransfer->addItem($itemTransfer);
            }
        }

        return $configurableBundleItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $groupKeyIndex
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function transformItemTransfer(ItemTransfer $itemTransfer, int $groupKeyIndex): ItemTransfer
    {
        return (new ItemTransfer())
            ->fromArray($itemTransfer->toArray(), true)
            ->setQuantity($itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot())
            ->setConfiguredBundle($this->transformConfiguredBundleTransfer($itemTransfer, $groupKeyIndex));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $groupKeyIndex
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function transformConfiguredBundleTransfer(ItemTransfer $itemTransfer, int $groupKeyIndex): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleTransfer())
            ->fromArray($itemTransfer->getConfiguredBundle()->toArray(), true)
            ->setQuantity(1)
            ->setGroupKey($this->generateTransformedGroupKey($itemTransfer, $groupKeyIndex));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $groupKeyIndex
     *
     * @return string
     */
    protected function generateTransformedGroupKey(ItemTransfer $itemTransfer, int $groupKeyIndex): string
    {
        return sprintf(
            '%s-%s',
            $itemTransfer->getConfiguredBundle()->getGroupKey(),
            $groupKeyIndex
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function transformItemTransferToItemCollectionTransfer(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
        if ($this->isItemQuantitySplittable($itemTransfer)) {
            return $this->salesFacade->transformSplittableItem($itemTransfer);
        }

        return (new ItemCollectionTransfer())->addItem($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemQuantitySplittable(ItemTransfer $itemTransfer): bool
    {
        if (!$itemTransfer->getIsQuantitySplittable()) {
            return false;
        }

        if ($this->isNonSplittableQuantityThresholdExceeded($itemTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isNonSplittableQuantityThresholdExceeded(ItemTransfer $itemTransfer): bool
    {
        $quantityThreshold = $this->salesConfigurableBundleConfig->findConfigurableBundleItemQuantityThreshold();
        if ($quantityThreshold === null) {
            return false;
        }

        return $itemTransfer->getQuantity() >= $quantityThreshold;
    }
}

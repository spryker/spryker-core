<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\OrderItem;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesQuantityFacadeInterface;

class ConfigurableBundleItemTransformer implements ConfigurableBundleItemTransformerInterface
{
    protected const SPLIT_CONFIGURABLE_BUNDLE_GROUP_KEY_PATTERN = '%s-%s';

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesQuantityFacadeInterface
     */
    protected $salesQuantityFacade;

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesQuantityFacadeInterface $salesQuantityFacade
     * @param \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        SalesConfigurableBundleToSalesQuantityFacadeInterface $salesQuantityFacade,
        SalesConfigurableBundleToSalesFacadeInterface $salesFacade
    ) {
        $this->salesQuantityFacade = $salesQuantityFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformConfigurableBundleItem(ItemTransfer $itemTransfer): ItemCollectionTransfer
    {
        $itemCollection = new ItemCollectionTransfer();

        $configuredBundleQuantity = $itemTransfer->getConfiguredBundle()->getQuantity();

        if ($configuredBundleQuantity > 1) {
            for ($index = 1; $index <= $configuredBundleQuantity; $index++) {
                $transformedItemTransfer = new ItemTransfer();
                $transformedItemTransfer->fromArray($itemTransfer->toArray(), true);

                $groupKey = sprintf(
                    static::SPLIT_CONFIGURABLE_BUNDLE_GROUP_KEY_PATTERN,
                    $itemTransfer->getConfiguredBundle()->getGroupKey(),
                    $index
                );

                $transformedItemTransfer->getConfiguredBundle()->setGroupKey($groupKey);
                $transformedItemTransfer->getConfiguredBundle()->setQuantity(1);
                $transformedItemTransfer->setQuantity(
                    $itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot()
                );

                if (!$this->salesQuantityFacade->isItemQuantitySplittable($transformedItemTransfer)) {
                    $itemCollection->addItem($itemTransfer);

                    continue;
                }

                $splitItemCollection = $this->salesFacade->transformSplittableItem($transformedItemTransfer);

                foreach ($splitItemCollection->getItems() as $splitItem) {
                    $itemCollection->addItem($splitItem);
                }
            }
        } else {
            if ($this->salesQuantityFacade->isItemQuantitySplittable($itemTransfer)) {
                $splitItemCollection = $this->salesFacade->transformSplittableItem($itemTransfer);

                foreach ($splitItemCollection->getItems() as $splitItem) {
                    $itemCollection->addItem($splitItem);
                }
            }
        }

        return $itemCollection;
    }
}

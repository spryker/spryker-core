<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Expander;

use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface
     */
    protected $productBundlePriceCalculation;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface $productBundlePriceCalculation
     */
    public function __construct(
        ProductBundleRepositoryInterface $productBundleRepository,
        ProductBundlePriceCalculationInterface $productBundlePriceCalculation
    ) {
        $this->productBundleRepository = $productBundleRepository;
        $this->productBundlePriceCalculation = $productBundlePriceCalculation;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemsWithProductBundles(array $itemTransfers): array
    {
        $bundleItemTransfers = $this->productBundleRepository->getBundleItemsBySalesOrderItemIds(
            $this->getSalesOrderItemIds($itemTransfers)
        );

        if (!$bundleItemTransfers) {
            return $itemTransfers;
        }

        foreach ($itemTransfers as $itemTransfer) {
            if (!isset($bundleItemTransfers[$itemTransfer->getIdSalesOrderItem()])) {
                continue;
            }

            $bundleItemTransfer = $bundleItemTransfers[$itemTransfer->getIdSalesOrderItem()];
            $itemTransfer->setProductBundle($bundleItemTransfer)
                ->setRelatedBundleItemIdentifier($bundleItemTransfer->getBundleItemIdentifier());
            $this->productBundlePriceCalculation->calculateBundleAmounts($bundleItemTransfer, $itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}

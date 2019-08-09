<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Spryker\Zed\SalesConfigurableBundle\Business\Calculator\ConfiguredBundlePriceCalculatorInterface;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface;

class SalesOrderConfiguredBundleExpander implements SalesOrderConfiguredBundleExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface
     */
    protected $salesConfigurableBundleRepository;

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Business\Calculator\ConfiguredBundlePriceCalculatorInterface
     */
    protected $configuredBundlePriceCalculator;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
     * @param \Spryker\Zed\SalesConfigurableBundle\Business\Calculator\ConfiguredBundlePriceCalculatorInterface $configuredBundlePriceCalculator
     */
    public function __construct(
        SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository,
        ConfiguredBundlePriceCalculatorInterface $configuredBundlePriceCalculator
    ) {
        $this->salesConfigurableBundleRepository = $salesConfigurableBundleRepository;
        $this->configuredBundlePriceCalculator = $configuredBundlePriceCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithConfiguredBundles(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemIds = $this->getSalesOrderItemIdsFromOrder($orderTransfer);

        $salesOrderConfiguredBundleFilterTransfer = (new SalesOrderConfiguredBundleFilterTransfer())
            ->setSalesOrderItemIds($salesOrderItemIds);

        $salesOrderConfiguredBundleTransfers = $this->salesConfigurableBundleRepository
            ->getSalesOrderConfiguredBundleCollectionByFilter($salesOrderConfiguredBundleFilterTransfer)
            ->getSalesOrderConfiguredBundles();

        $orderTransfer->setSalesOrderConfiguredBundles($salesOrderConfiguredBundleTransfers);

        $orderTransfer = $this->expandOrderItemsWithSalesOrderConfiguredBundleItems($orderTransfer, $salesOrderConfiguredBundleTransfers);
        $orderTransfer = $this->expandSalesOrderConfiguredBundlesWithPrices($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandSalesOrderConfiguredBundlesWithPrices(OrderTransfer $orderTransfer): OrderTransfer
    {
        $itemTransfers = $this->extractSalesOrderItems($orderTransfer);

        foreach ($orderTransfer->getSalesOrderConfiguredBundles() as $salesOrderConfiguredBundleTransfer) {
            $salesOrderConfiguredBundleTransfer->setPrice(
                $this->configuredBundlePriceCalculator->calculateSalesOrderConfiguredBundlePrice($salesOrderConfiguredBundleTransfer, $itemTransfers)
            );
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItemsWithSalesOrderConfiguredBundleItems(
        OrderTransfer $orderTransfer,
        ArrayObject $salesOrderConfiguredBundleTransfers
    ): OrderTransfer {
        $salesOrderConfiguredBundleItemTransfers = $this->extractSalesOrderConfiguredBundleItems($salesOrderConfiguredBundleTransfers);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (array_key_exists($itemTransfer->getIdSalesOrderItem(), $salesOrderConfiguredBundleItemTransfers)) {
                $itemTransfer->setSalesOrderConfiguredBundleItem(
                    $salesOrderConfiguredBundleItemTransfers[$itemTransfer->getIdSalesOrderItem()]
                );
            }
        }

        return $orderTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer[]
     */
    protected function extractSalesOrderConfiguredBundleItems(ArrayObject $salesOrderConfiguredBundleTransfers): array
    {
        $salesOrderConfiguredBundleItemTransfers = [];

        foreach ($salesOrderConfiguredBundleTransfers as $configuredBundleTransfer) {
            foreach ($configuredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
                $idSalesOrderItem = $salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem();
                $salesOrderConfiguredBundleItemTransfers[$idSalesOrderItem] = $salesOrderConfiguredBundleItemTransfer;
            }
        }

        return $salesOrderConfiguredBundleItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function extractSalesOrderItems(OrderTransfer $orderTransfer): array
    {
        $itemTransfers = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfers[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[]
     */
    protected function getSalesOrderItemIdsFromOrder(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface;

class SalesOrderConfiguredBundleExpander implements SalesOrderConfiguredBundleExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface
     */
    protected $salesConfigurableBundleRepository;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
     */
    public function __construct(
        SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
    ) {
        $this->salesConfigurableBundleRepository = $salesConfigurableBundleRepository;
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

        $orderTransfer = $this->expandOrderItems($orderTransfer, $salesOrderConfiguredBundleTransfers);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItems(
        OrderTransfer $orderTransfer,
        ArrayObject $salesOrderConfiguredBundleTransfers
    ): OrderTransfer {
        $salesOrderConfiguredBundleItemTransfers = $this->extractSalesOrderConfiguredBundleItems($salesOrderConfiguredBundleTransfers);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (array_key_exists($itemTransfer->getIdSalesOrderItem(), $salesOrderConfiguredBundleItemTransfers)) {
                $salesOrderConfiguredBundleItemTransfer = $salesOrderConfiguredBundleItemTransfers[$itemTransfer->getIdSalesOrderItem()];
                $itemTransfer->setSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer);
                $itemTransfer->setSalesOrderConfiguredBundle(
                    $this->findConfiguredBundleByConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer, $salesOrderConfiguredBundleTransfers)
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

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer|null
     */
    protected function findConfiguredBundleByConfiguredBundleItem(
        SalesOrderConfiguredBundleItemTransfer $salesOrderConfiguredBundleItemTransfer,
        ArrayObject $salesOrderConfiguredBundleTransfers
    ): ?SalesOrderConfiguredBundleTransfer {
        foreach ($salesOrderConfiguredBundleTransfers as $salesOrderConfiguredBundleTransfer) {
            if ($salesOrderConfiguredBundleItemTransfer->getIdSalesOrderConfiguredBundle() === $salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle()) {
                return $salesOrderConfiguredBundleTransfer;
            }
        }

        return null;
    }
}

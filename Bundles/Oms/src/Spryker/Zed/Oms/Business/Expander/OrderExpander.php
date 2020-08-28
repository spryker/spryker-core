<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Oms\OmsConfig;
use Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface
     */
    protected $flagChecker;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface $flagChecker
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(
        FlagCheckerInterface $flagChecker,
        OmsRepositoryInterface $omsRepository
    ) {
        $this->flagChecker = $flagChecker;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithOmsStates(OrderTransfer $orderTransfer): OrderTransfer
    {
        $itemStates = $this->getItemStates($orderTransfer);

        return $orderTransfer->setItemStates($itemStates);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function setOrderIsCancellableByItemState(array $orderTransfers): array
    {
        $mappedOrderTransfers = $this->mapOrdersByIdSalesOrder($orderTransfers);
        $mappedItemTransfers = $this->getMappedOrderItemsBySalesOrderIds(array_keys($mappedOrderTransfers));

        foreach ($mappedItemTransfers as $idSalesOrder => $itemTransfers) {
            $orderTransfer = $mappedOrderTransfers[$idSalesOrder] ?? null;

            if (!$orderTransfer) {
                continue;
            }

            $isOrderCancellable = $this->flagChecker->hasOrderItemsFlag($itemTransfers, OmsConfig::STATE_TYPE_FLAG_CANCELLABLE);
            $orderTransfer->setIsCancellable($isOrderCancellable);
        }

        return $orderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function mapOrdersByIdSalesOrder(array $orderTransfers): array
    {
        $mappedOrderTransfers = [];

        foreach ($orderTransfers as $orderTransfer) {
            $mappedOrderTransfers[$orderTransfer->getIdSalesOrder()] = $orderTransfer;
        }

        return $mappedOrderTransfers;
    }

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[][]
     */
    protected function getMappedOrderItemsBySalesOrderIds(array $salesOrderIds): array
    {
        $mappedItemTransfers = [];
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderIds($salesOrderIds);

        $itemTransfers = $this->omsRepository->getOrderItems($orderItemFilterTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            $mappedItemTransfers[$itemTransfer->getFkSalesOrder()][] = $itemTransfer;
        }

        return $mappedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function getItemStates(OrderTransfer $orderTransfer): array
    {
        $itemStates = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemStateTransfer = $itemTransfer->getState();

            if (!$itemStateTransfer || !$itemStateTransfer->getName()) {
                continue;
            }

            $itemStates[] = $itemStateTransfer->getName();
        }

        return array_values(array_unique($itemStates));
    }
}

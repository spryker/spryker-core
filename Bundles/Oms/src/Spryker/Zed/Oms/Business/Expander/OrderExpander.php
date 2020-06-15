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
        $indexOrderTransfers = $this->indexOrdersByIdSalesOrder($orderTransfers);
        $indexItemTransfers = $this->getIndexOrderItemsBySalesOrderIds(array_keys($indexOrderTransfers));

        foreach ($indexItemTransfers as $idSalesOrder => $itemTransfers) {
            $orderTransfer = $indexOrderTransfers[$idSalesOrder] ?? null;

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
    protected function indexOrdersByIdSalesOrder(array $orderTransfers): array
    {
        $indexOrderTransfers = [];

        foreach ($orderTransfers as $orderTransfer) {
            $indexOrderTransfers[$orderTransfer->getIdSalesOrder()] = $orderTransfer;
        }

        return $indexOrderTransfers;
    }

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[][]
     */
    protected function getIndexOrderItemsBySalesOrderIds(array $salesOrderIds): array
    {
        $indexItemTransfers = [];
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderIds($salesOrderIds);

        $itemTransfers = $this->omsRepository->getOrderItems($orderItemFilterTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            $indexItemTransfers[$itemTransfer->getFkSalesOrder()][] = $itemTransfer;
        }

        return $indexItemTransfers;
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

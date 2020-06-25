<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\AggregatedItemStateTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderAggregatedItemStateExpander implements OrderAggregatedItemStateExpanderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Expander\OrderItemStateExpanderInterface
     */
    protected $orderItemStateExpander;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Business\Expander\OrderItemStateExpanderInterface $orderItemStateExpander
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(
        OrderItemStateExpanderInterface $orderItemStateExpander,
        OmsRepositoryInterface $omsRepository
    ) {
        $this->orderItemStateExpander = $orderItemStateExpander;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function expandOrdersWithAggregatedItemStates(array $orderTransfers): array
    {
        $itemTransfers = $this->getOrderItems($orderTransfers);
        if (!$itemTransfers) {
            return $orderTransfers;
        }

        $itemTransfers = $this->orderItemStateExpander->expandOrderItemsWithItemState($itemTransfers);
        $aggregatedItemStateMap = $this->getAggregatedItemStateMap($itemTransfers);
        $orderTransfers = $this->updateOrders($orderTransfers, $aggregatedItemStateMap);

        return $orderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     * @param \Generated\Shared\Transfer\AggregatedItemStateTransfer[][] $aggregatedItemStateMap
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function updateOrders(array $orderTransfers, array $aggregatedItemStateMap): array
    {
        foreach ($orderTransfers as $orderTransfer) {
            $orderTransfer->setAggregatedItemStates(
                new ArrayObject(array_values($aggregatedItemStateMap[$orderTransfer->getIdSalesOrder()] ?? []))
            );
        }

        return $orderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\AggregatedItemStateTransfer[][]
     */
    protected function getAggregatedItemStateMap(array $itemTransfers): array
    {
        $aggregatedItemStateMap = [];
        foreach ($itemTransfers as $itemTransfer) {
            $idSalesOrder = $itemTransfer->getFkSalesOrder();
            $stateTransfer = $itemTransfer->getState();

            if (!$idSalesOrder || !$stateTransfer) {
                continue;
            }

            $aggregatedItemStateMap[$idSalesOrder][$stateTransfer->getName()] = (new AggregatedItemStateTransfer())
                ->setName($stateTransfer->getName())
                ->setDisplayName($stateTransfer->getDisplayName());
        }

        return $aggregatedItemStateMap;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItems(array $orderTransfers): array
    {
        $itemTransfers = $this->extractItemTransfersFromOrderTransfers($orderTransfers);
        if ($itemTransfers) {
            return $itemTransfers;
        }

        $orderReferences = $this->extractOrderReferences($orderTransfers);
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->setOrderReferences($orderReferences);

        return $this->omsRepository
            ->getOrderItems($orderItemFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return string[]
     */
    protected function extractOrderReferences(array $orderTransfers): array
    {
        $orderReferences = [];

        foreach ($orderTransfers as $orderTransfer) {
            $orderReferences[] = $orderTransfer->getOrderReference();
        }

        return $orderReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function extractItemTransfersFromOrderTransfers(array $orderTransfers): array
    {
        $itemTransfers = [];

        foreach ($orderTransfers as $orderTransfer) {
            $itemTransfers[] = $orderTransfer->getItems()->getArrayCopy();
        }

        return array_merge([], ...$itemTransfers);
    }
}

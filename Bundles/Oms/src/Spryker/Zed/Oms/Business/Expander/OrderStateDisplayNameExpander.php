<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderStateDisplayNameExpander implements OrderStateDisplayNameInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Expander\StateDisplayNameExpanderInterface
     */
    protected $stateDisplayNameExpander;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Business\Expander\StateDisplayNameExpanderInterface $stateDisplayNameExpander
     */
    public function __construct(
        StateDisplayNameExpanderInterface $stateDisplayNameExpander,
        OmsRepositoryInterface $omsRepository
    ) {
        $this->stateDisplayNameExpander = $stateDisplayNameExpander;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function expandOrdersWithItemStateDisplayNames(array $orderTransfers): array
    {
        $itemTransfers = $this->getOrderItems($orderTransfers);
        if (!$itemTransfers) {
            return $orderTransfers;
        }

        $itemTransfers = $this->stateDisplayNameExpander->expandOrderItemsWithStateDisplayName($itemTransfers);
        $groupedItemStateDisplayNames = $this->getItemStateDisplayNamesGroupedByIdSalesOrder($itemTransfers);
        $orderTransfers = $this->updateOrders($orderTransfers, $groupedItemStateDisplayNames);

        return $orderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     * @param string[][] $groupedItemStateDisplayNames
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function updateOrders(array $orderTransfers, array $groupedItemStateDisplayNames): array
    {
        foreach ($orderTransfers as $orderTransfer) {
            $orderTransfer->setItemStateDisplayNames(
                $groupedItemStateDisplayNames[$orderTransfer->getIdSalesOrder()] ?? []
            );
        }

        return $orderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[][]
     */
    protected function getItemStateDisplayNamesGroupedByIdSalesOrder(array $itemTransfers): array
    {
        $itemStateDisplayNames = [];

        foreach ($itemTransfers as $itemTransfer) {
            $idSalesOrder = $itemTransfer->getFkSalesOrder();
            $itemStateTransfer = $itemTransfer->getState();

            if (!$idSalesOrder || !$itemStateTransfer || !$itemStateTransfer->getDisplayName()) {
                continue;
            }

            $displayName = $itemStateTransfer->getDisplayName();
            $itemStateDisplayNames[$idSalesOrder][$displayName] = $displayName;
        }

        return $itemStateDisplayNames;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getOrderItems(array $orderTransfers): array
    {
        $itemTransfers = $this->extractItemTransfersFromOrderTransfers($orderTransfers);
        if ($itemTransfers) {
            return $itemTransfers;
        }

        $salesOrderReferences = $this->extractSalesOrderReferences($orderTransfers);
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->setOrderReferences($salesOrderReferences);

        return $this->omsRepository
            ->getOrderItems($orderItemFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return int[]
     */
    protected function extractSalesOrderReferences(array $orderTransfers): array
    {
        $salesOrderIds = [];

        foreach ($orderTransfers as $orderTransfer) {
            $salesOrderIds[] = $orderTransfer->getOrderReference();
        }

        return $salesOrderIds;
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

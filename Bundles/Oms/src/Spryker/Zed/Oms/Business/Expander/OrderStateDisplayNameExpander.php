<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderStateDisplayNameExpander implements OrderStateDisplayNameInterface
{
    protected const ITEM_STATE_GLOSSARY_KEY_PREFIX = 'oms.state.';

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
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
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
            if (!$idSalesOrder) {
                continue;
            }

            $displayName = $this->findDisplayName($itemTransfer);
            if (!$displayName) {
                continue;
            }

            $itemStateDisplayNames[$idSalesOrder][$displayName] = $displayName;
        }

        return $itemStateDisplayNames;
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

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function findDisplayName(ItemTransfer $itemTransfer): ?string
    {
        $stateTransfer = $itemTransfer->getState();
        if (!$stateTransfer) {
            return null;
        }

        if ($stateTransfer->getDisplayName()) {
            return $stateTransfer->getDisplayName();
        }

        $stateName = trim($stateTransfer->getName());
        $stateName = mb_strtolower($stateName);
        $stateName = str_replace(' ', '-', $stateName);
        $stateName = static::ITEM_STATE_GLOSSARY_KEY_PREFIX . $stateName;

        return $stateName;
    }
}

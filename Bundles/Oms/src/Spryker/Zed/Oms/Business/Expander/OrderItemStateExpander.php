<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderItemStateExpander implements OrderItemStateExpanderInterface
{
    protected const ITEM_STATE_GLOSSARY_KEY_PREFIX = 'oms.state.';

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface $finder
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(FinderInterface $finder, OmsRepositoryInterface $omsRepository)
    {
        $this->finder = $finder;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithItemState(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $orderItemFilterTransfer = $this->createOrderItemFilterTransfer($salesOrderItemIds);
        $orderItemTransfers = $this->omsRepository->getOrderItems($orderItemFilterTransfer);
        $mappedItemTransfersByIdSalesOrderItem = $this->mapItemsByIdSalesOrderItem($orderItemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $mappedItemTransfer = $mappedItemTransfersByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItem()] ?? null;
            if (!$mappedItemTransfer) {
                continue;
            }

            [$displayName, $stateName] = $this->finder->findItemStateDisplayName($mappedItemTransfer);
            $normalizedItemStateName = $this->getNormalizedItemStateName($stateName);
            if (!$displayName) {
                $displayName = static::ITEM_STATE_GLOSSARY_KEY_PREFIX . $normalizedItemStateName;
            }

            $itemTransfer = $this->setItemState($itemTransfer, $normalizedItemStateName, $displayName);
        }

        return $itemTransfers;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getNormalizedItemStateName(string $name): string
    {
        $stateName = str_replace(' ', '-', mb_strtolower(trim($name)));

        return $stateName;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapItemsByIdSalesOrderItem(array $itemTransfers): array
    {
        $mappedItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $mappedItemTransfers[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $mappedItemTransfers;
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function createOrderItemFilterTransfer(array $salesOrderItemIds): OrderItemFilterTransfer
    {
        return (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $name
     * @param string $displayName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setItemState(ItemTransfer $itemTransfer, string $name, string $displayName): ItemTransfer
    {
        if (!$itemTransfer->getState()) {
            $itemTransfer->setState(new ItemStateTransfer());
        }

        $itemTransfer->getState()
            ->setDisplayName($displayName)
            ->setName($name);

        return $itemTransfer;
    }
}

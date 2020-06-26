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
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds);
        $persistenceItems = $this->omsRepository->getOrderItems($orderItemFilterTransfer);
        $persistenceItemMapByIdSalesOrderItem = $this->mapItemsByIdSalesOrderItem($persistenceItems);

        foreach ($itemTransfers as $itemTransfer) {
            $persistenceItemTransfer = $persistenceItemMapByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItem()] ?? null;
            if (!$persistenceItemTransfer) {
                continue;
            }

            [$displayName, $stateName] = $this->finder->findItemStateDisplayName($persistenceItemTransfer);
            if (!$stateName) {
                continue;
            }

            $itemTransfer = $this->setItemState($itemTransfer, $stateName, $displayName);
        }

        return $itemTransfers;
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $stateName
     * @param string|null $displayName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setItemState(ItemTransfer $itemTransfer, string $stateName, ?string $displayName): ItemTransfer
    {
        if (!$itemTransfer->getState()) {
            $itemTransfer->setState(new ItemStateTransfer());
        }

        if (!$displayName) {
            $displayName = $this->getFallbackDisplayName($stateName);
        }

        $itemTransfer->getState()
            ->setDisplayName($displayName)
            ->setName($stateName);

        return $itemTransfer;
    }

    /**
     * @param string $stateName
     *
     * @return string
     */
    protected function getFallbackDisplayName(string $stateName): string
    {
        return static::ITEM_STATE_GLOSSARY_KEY_PREFIX . str_replace(' ', '-', mb_strtolower(trim($stateName)));
    }
}

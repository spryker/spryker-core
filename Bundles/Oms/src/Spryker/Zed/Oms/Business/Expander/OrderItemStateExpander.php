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
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderItemStateExpander implements OrderItemStateExpanderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $omsConfig;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface $finder
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     */
    public function __construct(FinderInterface $finder, OmsRepositoryInterface $omsRepository, OmsConfig $omsConfig)
    {
        $this->finder = $finder;
        $this->omsRepository = $omsRepository;
        $this->omsConfig = $omsConfig;
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

            $state = $this->finder->findStateByName($persistenceItemTransfer);
            if (!$state) {
                continue;
            }

            $itemTransfer = $this->setItemState($itemTransfer, $state);
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
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return string
     */
    protected function getDisplayName(StateInterface $state): string
    {
        if ($state->getDisplay()) {
            return $state->getDisplay();
        }

        return $this->omsConfig->getFallbackDisplayNamePrefix() . str_replace(' ', '-', mb_strtolower(trim($state->getName())));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setItemState(ItemTransfer $itemTransfer, StateInterface $state): ItemTransfer
    {
        if (!$itemTransfer->getState()) {
            $itemTransfer->setState(new ItemStateTransfer());
        }

        $itemTransfer->getState()
            ->setDisplayName($this->getDisplayName($state))
            ->setName($state->getName());

        return $itemTransfer;
    }
}

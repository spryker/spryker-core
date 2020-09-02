<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business\Expander;

use Generated\Shared\Transfer\SalesOrderItemConfigurationFilterTransfer;
use Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationRepositoryInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationRepositoryInterface
     */
    protected $salesProductConfigurationRepository;

    /**
     * @param \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationRepositoryInterface $salesProductConfigurationRepository
     */
    public function __construct(SalesProductConfigurationRepositoryInterface $salesProductConfigurationRepository)
    {
        $this->salesProductConfigurationRepository = $salesProductConfigurationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductConfiguration(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->getSalesOrderItemIds($itemTransfers);
        $salesOrderItemConfigurationTransfers = $this->salesProductConfigurationRepository
            ->getSalesOrderItemConfigurationsByFilter((new SalesOrderItemConfigurationFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds));

        $indexedSalesOrderItemConfigurationTransfers = $this->indexSalesOrderItemConfigurations($salesOrderItemConfigurationTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemConfigurationTransfer = $indexedSalesOrderItemConfigurationTransfers[$itemTransfer->getIdSalesOrderItem()] ?? null;

            if ($salesOrderItemConfigurationTransfer) {
                $itemTransfer->setSalesOrderItemConfiguration($salesOrderItemConfigurationTransfer);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem()) {
                $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
            }
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer[] $salesOrderItemConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer[]
     */
    protected function indexSalesOrderItemConfigurations(array $salesOrderItemConfigurationTransfers): array
    {
        $indexedSalesOrderItemConfigurationTransfers = [];

        foreach ($salesOrderItemConfigurationTransfers as $salesOrderItemConfigurationTransfer) {
            $indexedSalesOrderItemConfigurationTransfers[$salesOrderItemConfigurationTransfer->getIdSalesOrderItem()] =
                $salesOrderItemConfigurationTransfer;
        }

        return $indexedSalesOrderItemConfigurationTransfers;
    }
}

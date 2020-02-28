<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderReferenceExpander implements OrderReferenceExpanderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(SalesRepositoryInterface $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithOrderReference(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $orderReferences = $this->salesRepository->getOrderReferencesByOrderItemIds($salesOrderItemIds);

        $mappedOrderReferences = $this->mapOrderReferencesByIdSalesOrderItem($orderReferences);

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setOrderReference($mappedOrderReferences[$itemTransfer->getIdSalesOrderItem()] ?? null);
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
            $itemTransfer->requireIdSalesOrderItem();

            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param string[][] $orderReferences
     *
     * @return string[]
     */
    protected function mapOrderReferencesByIdSalesOrderItem(array $orderReferences): array
    {
        $mappedOrderReferences = [];

        foreach ($orderReferences as $orderReference) {
            $mappedOrderReferences[$orderReference[ItemTransfer::ID_SALES_ORDER_ITEM]] = $orderReference[ItemTransfer::ORDER_REFERENCE];
        }

        return $mappedOrderReferences;
    }
}

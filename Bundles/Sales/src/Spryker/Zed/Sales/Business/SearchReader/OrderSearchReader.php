<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\SearchReader;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderSearchReader implements OrderSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface[]
     */
    protected $orderSearchQueryExpanderPlugins;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[]
     */
    protected $searchOrderExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[] $searchOrderExpanderPlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderQueryExpanderPluginInterface[] $orderSearchQueryExpanderPlugins
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        array $searchOrderExpanderPlugins,
        array $orderSearchQueryExpanderPlugins
    ) {
        $this->salesRepository = $salesRepository;
        $this->searchOrderExpanderPlugins = $searchOrderExpanderPlugins;
        $this->orderSearchQueryExpanderPlugins = $orderSearchQueryExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer = $this->executeOrderSearchQueryExpanderPlugins($orderListTransfer);

        $orderListTransfer = $this->salesRepository->searchOrders($orderListTransfer);
        $orderListTransfer = $this->expandOrdersWithOrderTotals($orderListTransfer);
        $orderListTransfer = $this->expandOrdersWithOrderItems($orderListTransfer);

        $orderTransfers = $this->executeSearchOrderExpanderPlugins(
            $orderListTransfer->getOrders()->getArrayCopy()
        );

        $orderListTransfer->getOrders()->exchangeArray($orderTransfers);

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function expandOrdersWithOrderItems(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        if (!$orderListTransfer->getFormat()->getExpandWithItems()) {
            return $orderListTransfer;
        }

        $salesOrderIds = $this->getSalesOrderIdsFromOrderTransfers($orderListTransfer->getOrders());
        $mappedOrderTransfers = $this->mapOrderTransfersByOrderReference($orderListTransfer->getOrders());

        $itemTransfers = $this->salesRepository->getSalesOrderItemsByOrderIds($salesOrderIds);
        $itemTransfers = $this->deriveOrderItemsUnitPrices($itemTransfers);

        $orderTransfers = $this->mapOrderItemTransfersToOrderTransfers(
            $itemTransfers,
            $mappedOrderTransfers
        );

        $orderListTransfer->setOrders(new ArrayObject($orderTransfers));

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function expandOrdersWithOrderTotals(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $salesOrderIds = $this->getSalesOrderIdsFromOrderTransfers($orderListTransfer->getOrders());

        $mappedTotalsTransfers = $this->salesRepository->getMappedSalesOrderTotalsBySalesOrderIds($salesOrderIds);

        $orderTransfers = $this->mapSalesOrderTotalsTransfersToOrderTransfers(
            $mappedTotalsTransfers,
            $orderListTransfer->getOrders()
        );

        $orderListTransfer->setOrders(new ArrayObject($orderTransfers));

        return $orderListTransfer;
    }

    /**
     * Unit prices are populated for presentation purposes only. For further calculations use sum prices or properly populated unit prices.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function deriveOrderItemsUnitPrices(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer
                ->setUnitGrossPrice((int)round($itemTransfer->getSumGrossPrice() / $itemTransfer->getQuantity()))
                ->setUnitNetPrice((int)round($itemTransfer->getSumNetPrice() / $itemTransfer->getQuantity()))
                ->setUnitPrice((int)round($itemTransfer->getSumPrice() / $itemTransfer->getQuantity()))
                ->setUnitSubtotalAggregation((int)round($itemTransfer->getSumSubtotalAggregation() / $itemTransfer->getQuantity()))
                ->setUnitDiscountAmountAggregation((int)round($itemTransfer->getSumDiscountAmountAggregation() / $itemTransfer->getQuantity()))
                ->setUnitDiscountAmountFullAggregation((int)round($itemTransfer->getSumDiscountAmountFullAggregation() / $itemTransfer->getQuantity()))
                ->setUnitExpensePriceAggregation((int)round($itemTransfer->getSumExpensePriceAggregation() / $itemTransfer->getQuantity()))
                ->setUnitTaxAmount((int)round($itemTransfer->getSumTaxAmount() / $itemTransfer->getQuantity()))
                ->setUnitTaxAmountFullAggregation((int)round($itemTransfer->getSumTaxAmountFullAggregation() / $itemTransfer->getQuantity()))
                ->setUnitPriceToPayAggregation((int)round($itemTransfer->getSumPriceToPayAggregation() / $itemTransfer->getQuantity()));
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function executeOrderSearchQueryExpanderPlugins(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();
        $filterTransfers = $orderListTransfer->getFilterFields()->getArrayCopy();

        foreach ($this->orderSearchQueryExpanderPlugins as $orderSearchQueryExpanderPlugin) {
            if ($orderSearchQueryExpanderPlugin->isApplicable($filterTransfers)) {
                $queryJoinCollectionTransfer = $orderSearchQueryExpanderPlugin->expand(
                    $filterTransfers,
                    $queryJoinCollectionTransfer
                );
            }
        }

        return $orderListTransfer->setQueryJoins($queryJoinCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function executeSearchOrderExpanderPlugins(array $orderTransfers): array
    {
        foreach ($this->searchOrderExpanderPlugins as $searchOrderExpanderPlugin) {
            $orderTransfers = $searchOrderExpanderPlugin->expand($orderTransfers);
        }

        return $orderTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return int[]
     */
    protected function getSalesOrderIdsFromOrderTransfers(ArrayObject $orderTransfers): array
    {
        $salesOrderIds = [];

        foreach ($orderTransfers as $orderTransfer) {
            $salesOrderIds[] = $orderTransfer->getIdSalesOrder();
        }

        return $salesOrderIds;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function mapOrderTransfersByOrderReference(ArrayObject $orderTransfers): array
    {
        $mappedOrderTransfer = [];

        foreach ($orderTransfers as $orderTransfer) {
            $mappedOrderTransfer[$orderTransfer->getOrderReference()] = $orderTransfer;
        }

        return $mappedOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function mapOrderItemTransfersToOrderTransfers(array $itemTransfers, array $orderTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $orderTransfer = $orderTransfers[$itemTransfer->getOrderReference()] ?? null;

            if ($orderTransfer) {
                $orderTransfer->addItem($itemTransfer);
            }
        }

        return array_values($orderTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer[] $totalsTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    public function mapSalesOrderTotalsTransfersToOrderTransfers(array $totalsTransfers, ArrayObject $orderTransfers): ArrayObject
    {
        foreach ($orderTransfers as $orderTransfer) {
            $totalsTransfer = $totalsTransfers[$orderTransfer->getIdSalesOrder()] ?? null;

            if ($totalsTransfer) {
                $orderTransfer->setTotals($totalsTransfer);
            }
        }

        return $orderTransfers;
    }
}

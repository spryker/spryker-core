<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException;

class OrderTotalsAggregator implements OrderTotalsAggregatorInterface
{

    /**
     * @var \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected $orderAmountAggregators;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected $itemAmountAggregators;

    /**
     * @param \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[] $orderAmountAggregators
     * @param \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[] $itemAmountAggregators
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $SalesAggregatorQueryContainer
     */
    public function __construct(
        array $orderAmountAggregators,
        array $itemAmountAggregators,
        SalesQueryContainerInterface $SalesAggregatorQueryContainer
    ) {
        $this->orderAmountAggregators = $orderAmountAggregators;
        $this->salesQueryContainer = $SalesAggregatorQueryContainer;
        $this->itemAmountAggregators = $itemAmountAggregators;
    }

    /**
     * @param int $idSalesAggregatorOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateByIdSalesAggregatorOrder($idSalesAggregatorOrder)
    {
        $orderTransfer = $this->hydrateOrderTransfer($idSalesAggregatorOrder);
        $orderTransfer = $this->applyItemAmountAggregatorPlugins($orderTransfer);

        return $this->applyAmountAggregatorsToOrderTransfer($orderTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function aggregateByIdSalesAggregatorOrderItem($idSalesOrderItem)
    {
        $salesOrderItemEntity = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($idSalesOrderItem);

        if (empty($salesOrderItemEntity)) {
            throw new OrderTotalHydrationException(
                sprintf('SalesAggregator order item with id "%d" not found!', $idSalesOrderItem)
            );
        }

        $itemTransfer = $this->getHydratedSaleOrderItemTransfer($salesOrderItemEntity);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->addItem($itemTransfer);

        $this->applyItemAmountAggregatorPlugins($orderTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateByOrderTransfer(OrderTransfer $orderTransfer)
    {
        $orderTransfer = $this->applyItemAmountAggregatorPlugins($orderTransfer);

        return $this->applyAmountAggregatorsToOrderTransfer($orderTransfer);
    }

    /**
     * @param int $idSalesAggregatorOrder
     *
     * @throws \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateOrderTransfer($idSalesAggregatorOrder)
    {
        $salesOrderEntity = $this->salesQueryContainer->querySalesOrder()
            ->findOneByIdSalesOrder($idSalesAggregatorOrder);

        if (empty($salesOrderEntity)) {
            throw new OrderTotalHydrationException(
                sprintf('SalesAggregator order with id "%d" not found!', $idSalesAggregatorOrder)
            );
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($salesOrderEntity->toArray(), true);

        foreach ($salesOrderEntity->getItems() as $orderItemEntity) {
            $itemTransfer = $this->getHydratedSaleOrderItemTransfer($orderItemEntity);
            $orderTransfer->addItem($itemTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function applyItemAmountAggregatorPlugins(OrderTransfer $orderTransfer)
    {
        foreach ($this->itemAmountAggregators as $orderAmountAggregator) {
            $orderAmountAggregator->aggregate($orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function applyAmountAggregatorsToOrderTransfer(OrderTransfer $orderTransfer)
    {
        foreach ($this->orderAmountAggregators as $orderAmountAggregator) {
            $orderAmountAggregator->aggregate($orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getHydratedSaleOrderItemTransfer(SpySalesOrderItem $salesOrderItemEntity)
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->fromArray($salesOrderItemEntity->toArray(), true);
        $itemTransfer->setUnitGrossPrice($salesOrderItemEntity->getGrossPrice());

        return $itemTransfer;
    }

}

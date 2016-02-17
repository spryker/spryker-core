<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Discount\Communication\Plugin\Collector\Item;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\Business\Exception\OrderTotalHydrationException;

class OrderTotalsAggregator
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected $orderAmountAggregators;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var array|\Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected $itemAmountAggregators;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[] $orderAmountAggregators
     * @param \Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[] $itemAmountAggregators
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(
        array $orderAmountAggregators = [],
        array $itemAmountAggregators = [],
        SalesQueryContainerInterface $salesQueryContainer
    ) {
        $this->orderAmountAggregators = $orderAmountAggregators;
        $this->salesQueryContainer = $salesQueryContainer;
        $this->itemAmountAggregators = $itemAmountAggregators;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateByIdSalesOrder($idSalesOrder)
    {
        $orderTransfer = $this->hydrateOrderTransfer($idSalesOrder);
        $orderTransfer = $this->applyItemAmountAggregatorPlugins($orderTransfer);

        return $this->applyAmountAggregatorsToOrderTransfer($orderTransfer);

    }

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\OrderTotalHydrationException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function aggregateByIdSalesOrderItem($idSalesOrderItem)
    {
        $salesOrderItemEntity = $this->salesQueryContainer
            ->querySalesOrderItem()
            ->findOneByIdSalesOrderItem($idSalesOrderItem);

        if (empty($salesOrderItemEntity)) {
            throw new OrderTotalHydrationException(
                sprintf('Sales order item with id "%d" not found!', $idSalesOrderItem)
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
     * @param int $idSalesOrder
     * @return \Generated\Shared\Transfer\OrderTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\OrderTotalHydrationException
     */
    protected function hydrateOrderTransfer($idSalesOrder)
    {
        $salesOrderEntity = $this->salesQueryContainer->querySalesOrder()->findOneByIdSalesOrder($idSalesOrder);

        if (empty($salesOrderEntity)) {
            throw new OrderTotalHydrationException(
                sprintf('Sales order with id "%d" not found!', $idSalesOrder)
            );
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($salesOrderEntity->toArray(), true);

        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $orderTransfer->addItem(
                $this->getHydratedSaleOrderItemTransfer($salesOrderItemEntity)
            );
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

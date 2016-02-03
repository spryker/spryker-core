<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

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
     * @param \Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface[] $orderAmountAggregators
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     */
    public function __construct(
        array $orderAmountAggregators = [],
        SalesQueryContainerInterface $salesQueryContainer
    ) {
        $this->orderAmountAggregators = $orderAmountAggregators;
        $this->salesQueryContainer = $salesQueryContainer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateByIdSalesOrder($idSalesOrder)
    {
        $orderTransfer = $this->hydrateOrderTransfer($idSalesOrder);

        return $this->applyAmountAggregatorsToOrderTransfer($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return $orderTransfer
     */
    public function aggregateByOrderTransfer(OrderTransfer $orderTransfer)
    {
        return $this->applyAmountAggregatorsToOrderTransfer($orderTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrateOrderTransfer($idSalesOrder)
    {
        $salesOrderEntity = $this->salesQueryContainer->querySalesOrder()->findOneByIdSalesOrder($idSalesOrder);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($salesOrderEntity->toArray(), true);

        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer->fromArray($salesOrderItemEntity->toArray(), true);
            $itemTransfer->setUnitGrossPrice($salesOrderItemEntity->getGrossPrice());
            $orderTransfer->addItem($itemTransfer);
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

}

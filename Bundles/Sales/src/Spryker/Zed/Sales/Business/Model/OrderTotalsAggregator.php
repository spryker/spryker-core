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
     * @var OrderTotalsAggregatePluginInterface[]
     */
    protected $orderAmountAggregators;

    /**
     * @var SalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @param OrderTotalsAggregatePluginInterface[] $orderAmountAggregators
     * @param SalesQueryContainerInterface $salesQueryContainer
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
     * @return OrderTransfer
     */
    public function aggregateByIdSalesOrder($idSalesOrder)
    {
        $orderTransfer = $this->hydrateOrderTransfer($idSalesOrder);

        foreach ($this->orderAmountAggregators as $orderAmountAggregator) {
            $orderAmountAggregator->aggregate($orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
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

            foreach ($salesOrderItemEntity->getOptions() as $productOptionEntity) {
                $productOptionTransfer = new ProductOptionTransfer();
                $productOptionTransfer->fromArray($productOptionEntity->toArray(), true);
                $productOptionTransfer->setUnitGrossPrice($productOptionEntity->getGrossPrice());
            }
            
            $orderTransfer->addItem($itemTransfer);
        }

        return $orderTransfer;
    }

}

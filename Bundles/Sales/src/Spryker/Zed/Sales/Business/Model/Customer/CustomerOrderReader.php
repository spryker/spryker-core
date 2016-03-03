<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CustomerOrderReader implements CustomerOrderReaderInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var SalesToSalesAggregatorInterface
     */
    protected $salesAggregatorFacade;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param SalesToSalesAggregatorInterface $salesAggregatorFacade
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToSalesAggregatorInterface $salesAggregatorFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->salesAggregatorFacade = $salesAggregatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        $orderCollection = $this
            ->queryContainer
            ->queryCustomerOrders($idCustomer, $orderListTransfer->getFilter())
            ->find();

        $orderList = $this->hydrateOrderListCollectionTransferFromEntityCollection($orderCollection);

        $orderListTransfer->setOrders($orderList);

        return $orderListTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orderCollection
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function hydrateOrderListCollectionTransferFromEntityCollection(ObjectCollection $orderCollection)
    {
        $orderList = new \ArrayObject();
        foreach ($orderCollection as $salesOrderEntity) {
            $orderTransfer = new OrderTransfer();
            $orderTransfer->fromArray($salesOrderEntity->toArray(), true);

            $orderTransfer = $this->salesAggregatorFacade->getOrderTotalByOrderTransfer($orderTransfer);
            $orderList->append($orderTransfer);
        }

        return $orderList;

    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use Generated\Shared\Transfer\OrderListTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CustomerOrderReader implements CustomerOrderReaderInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface
     */
    protected $salesAggregatorFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    protected $orderHydrator;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface $salesAggregatorFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface $orderHydrator
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToSalesAggregatorInterface $salesAggregatorFacade,
        OrderHydratorInterface $orderHydrator
    ) {
        $this->queryContainer = $queryContainer;
        $this->salesAggregatorFacade = $salesAggregatorFacade;
        $this->orderHydrator = $orderHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        $orderCollection = $this
            ->queryContainer
            ->queryCustomerOrders($idCustomer, $orderListTransfer->getFilter())
            ->find();

        $orders = $this->hydrateOrderListCollectionTransferFromEntityCollection($orderCollection);

        $orderListTransfer->setOrders($orders);

        return $orderListTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orderCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function hydrateOrderListCollectionTransferFromEntityCollection(ObjectCollection $orderCollection)
    {
        $orders = new \ArrayObject();
        foreach ($orderCollection as $salesOrderEntity) {
            if (count($salesOrderEntity->getItems()) == 0) {
                continue;
            }

            $orderTransfer = $this->orderHydrator->hydrateOrderTransferFromPersistenceByIdSalesOrder(
                $salesOrderEntity->getIdSalesOrder()
            );
            $orders->append($orderTransfer);
        }

        return $orders;
    }

}

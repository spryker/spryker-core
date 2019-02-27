<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class FilteredCustomerOrderListReader implements FilteredCustomerOrderListReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface
     */
    protected $orderHydrator;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface $orderHydrator
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        OrderHydratorInterface $orderHydrator,
        SalesToOmsInterface $omsFacade
    ) {
        $this->salesRepository = $salesRepository;
        $this->orderHydrator = $orderHydrator;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->requireCustomerReference();
        $orderList = $this->salesRepository->getCustomerOrderListByCustomerReference($orderListTransfer)->getOrders();

        if (!$orderList->count()) {
            return $orderListTransfer;
        }

        $orders = $this->hydrateOrderListCollectionTransferFromEntityCollection(new ObjectCollection($orderList));
        $orderListTransfer->setOrders($orders);

        return $orderListTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrder[] $orderCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function hydrateOrderListCollectionTransferFromEntityCollection(ObjectCollection $orderCollection)
    {
        $orders = new ArrayObject();
        foreach ($orderCollection as $salesOrderEntity) {
            if ($salesOrderEntity->countItems() === 0) {
                continue;
            }

            $idSalesOrder = $salesOrderEntity->getIdSalesOrder();
            if ($this->omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder)) {
                continue;
            }

            $orderTransfer = $this->orderHydrator->hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder);
            $orders->append($orderTransfer);
        }

        return $orders;
    }
}

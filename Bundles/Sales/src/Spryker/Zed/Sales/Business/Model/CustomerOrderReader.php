<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class CustomerOrderReader implements CustomerOrderReaderInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        $orderListTransfer->setOrders(new \ArrayObject());

        $orderCollection = $this
            ->queryContainer
            ->queryCustomerOrders($idCustomer, $orderListTransfer->getFilter())
            ->find();

        foreach ($orderCollection as $salesOrderEntity) {
            $orderTransfer = new OrderTransfer();
            $orderTransfer->fromArray($salesOrderEntity->toArray(), true);

            $orderListTransfer->addOrder($orderTransfer);
        }

        return $orderListTransfer;
    }

}

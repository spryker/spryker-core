<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use ArrayObject;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OffsetPaginatedCustomerOrderListReader implements OffsetPaginatedCustomerOrderListReaderInterface
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
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[]
     */
    protected $searchOrderExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface $orderHydrator
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[] $searchOrderExpanderPlugins
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        OrderHydratorInterface $orderHydrator,
        SalesToOmsInterface $omsFacade,
        array $searchOrderExpanderPlugins
    ) {
        $this->salesRepository = $salesRepository;
        $this->orderHydrator = $orderHydrator;
        $this->omsFacade = $omsFacade;
        $this->searchOrderExpanderPlugins = $searchOrderExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer
    {
        $orderListRequestTransfer->requireCustomerReference();
        $orderListTransfer = $this->salesRepository->getCustomerOrderListByCustomerReference($orderListRequestTransfer);

        if (!$orderListTransfer->getOrders()->count()) {
            return $orderListTransfer;
        }

        $orderListTransfer = $this->hydrateOrderTransfersInOrderListTransfer($orderListTransfer);
        $orderTransfers = $this->executeSearchOrderExpanderPlugins($orderListTransfer->getOrders()->getArrayCopy());
        $orderListTransfer->setOrders(new ArrayObject($orderTransfers));

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function hydrateOrderTransfersInOrderListTransfer(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderTransfers = new ArrayObject();
        foreach ($orderListTransfer->getOrders() as $orderTransfer) {
            $idSalesOrder = $orderTransfer->getIdSalesOrder();
            if ($this->omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder)) {
                continue;
            }

            $orderTransfers->append($this->orderHydrator->hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder));
        }

        return $orderListTransfer->setOrders($orderTransfers);
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
}

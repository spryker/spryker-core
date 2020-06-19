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
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderListExpanderPluginInterface[]
     */
    protected $orderListExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderHydratorInterface $orderHydrator
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderListExpanderPluginInterface[] $orderListExpanderPlugins
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        OrderHydratorInterface $orderHydrator,
        SalesToOmsInterface $omsFacade,
        array $orderListExpanderPlugins = []
    ) {
        $this->salesRepository = $salesRepository;
        $this->orderHydrator = $orderHydrator;
        $this->omsFacade = $omsFacade;
        $this->orderListExpanderPlugins = $orderListExpanderPlugins;
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
        $orderListTransfer = $this->executeOrderListExpanderPlugins($orderListTransfer);

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
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function executeOrderListExpanderPlugins(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        foreach ($this->orderListExpanderPlugins as $orderListExpanderPlugin) {
            $orderListTransfer = $orderListExpanderPlugin->expand($orderListTransfer);
        }

        return $orderListTransfer;
    }
}

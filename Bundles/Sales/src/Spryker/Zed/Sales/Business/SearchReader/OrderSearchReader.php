<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\SearchReader;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderSearchReader implements OrderSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[]
     */
    protected $orderExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[] $orderExpanderPlugins
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        array $orderExpanderPlugins
    ) {
        $this->salesRepository = $salesRepository;
        $this->orderExpanderPlugins = $orderExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer = $this->salesRepository->searchOrders($orderListTransfer);

        $orderTransfers = $this->expandOrderTransfers(
            $orderListTransfer->getOrders()
        );

        return $orderListTransfer->setOrders($orderTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function expandOrderTransfers(ArrayObject $orderTransfers): ArrayObject
    {
        $expandedOrderTransfers = [];

        foreach ($orderTransfers as $orderTransfer) {
            $expandedOrderTransfers[] = $this->executeOrderExpanderPlugins($orderTransfer);
        }

        $orderTransfers->exchangeArray($expandedOrderTransfers);

        return $orderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeOrderExpanderPlugins(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($this->orderExpanderPlugins as $orderExpanderPlugin) {
            $orderTransfer = $orderExpanderPlugin->hydrate($orderTransfer);
        }

        return $orderTransfer;
    }
}

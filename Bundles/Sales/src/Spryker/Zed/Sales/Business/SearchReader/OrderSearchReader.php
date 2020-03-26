<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\SearchReader;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderSearchReader implements OrderSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderSearchQueryExpanderPluginInterface[]
     */
    protected $orderSearchQueryExpanderPlugins;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[]
     */
    protected $searchOrderExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\SearchOrderExpanderPluginInterface[] $searchOrderExpanderPlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderSearchQueryExpanderPluginInterface[] $orderSearchQueryExpanderPlugins
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        array $searchOrderExpanderPlugins,
        array $orderSearchQueryExpanderPlugins
    ) {
        $this->salesRepository = $salesRepository;
        $this->searchOrderExpanderPlugins = $searchOrderExpanderPlugins;
        $this->orderSearchQueryExpanderPlugins = $orderSearchQueryExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer = $this->executeOrderSearchQueryExpanderPlugins($orderListTransfer);

        $orderListTransfer = $this->salesRepository->searchOrders($orderListTransfer);

        $orderTransfers = $this->executeSearchOrderExpanderPlugins(
            $orderListTransfer->getOrders()->getArrayCopy()
        );

        $orderListTransfer->getOrders()->exchangeArray($orderTransfers);

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function executeOrderSearchQueryExpanderPlugins(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();
        $filterTransfers = $orderListTransfer->getFilterFields()->getArrayCopy();

        foreach ($this->orderSearchQueryExpanderPlugins as $orderSearchQueryExpanderPlugin) {
            if ($orderSearchQueryExpanderPlugin->isApplicable($filterTransfers)) {
                $queryJoinCollectionTransfer = $orderSearchQueryExpanderPlugin->expand(
                    $filterTransfers,
                    $queryJoinCollectionTransfer
                );
            }
        }

        return $orderListTransfer->setQueryJoins($queryJoinCollectionTransfer);
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Reader;

use Generated\Shared\Transfer\OrderCollectionTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface>
     */
    protected $hydrateOrderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface> $hydrateOrderPlugins
     */
    public function __construct(SalesRepositoryInterface $salesRepository, array $hydrateOrderPlugins = [])
    {
        $this->salesRepository = $salesRepository;
        $this->hydrateOrderPlugins = $hydrateOrderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(OrderFilterTransfer $orderFilterTransfer): OrderTransfer
    {
        $orderTransfer = $this->salesRepository->getSalesOrderDetails($orderFilterTransfer);
        $orderTransfer = $this->expandOrderTransferWithOrderTotals($orderTransfer);
        if ($orderFilterTransfer->getWithUniqueProductCount() !== false) {
            $orderTransfer = $this->expandOrderTransferWithUniqueProductsQuantity($orderTransfer, $orderFilterTransfer);
        }
        $orderTransfer = $this->executeHydrateOrderPlugins($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCriteriaTransfer $orderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCollectionTransfer
     */
    public function getOrderCollection(OrderCriteriaTransfer $orderCriteriaTransfer): OrderCollectionTransfer
    {
        $orderCollectionTransfer = $this->salesRepository->getOrderCollection($orderCriteriaTransfer);
        if (
            $orderCriteriaTransfer->getOrderConditions()
            && $orderCriteriaTransfer->getOrderConditionsOrFail()->getWithOrderExpanderPlugins()
        ) {
            foreach ($orderCollectionTransfer->getOrders() as $orderTransfer) {
                $this->executeHydrateOrderPlugins($orderTransfer);
            }
        }

        return $orderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderTransferWithOrderTotals(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer->setTotalOrderCount(0);
        if ($orderTransfer->getCustomerReference()) {
            $totalCustomerOrderCount = $this->salesRepository->getTotalCustomerOrderCount($orderTransfer);
            $orderTransfer->setTotalOrderCount($totalCustomerOrderCount);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderTransferWithUniqueProductsQuantity(OrderTransfer $orderTransfer, OrderFilterTransfer $orderFilterTransfer): OrderTransfer
    {
        $uniqueProductQuantity = $this->salesRepository->countUniqueProductsForOrder($orderFilterTransfer->getSalesOrderId());
        $orderTransfer->setUniqueProductQuantity($uniqueProductQuantity);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeHydrateOrderPlugins(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($this->hydrateOrderPlugins as $hydrateOrderPlugin) {
            $orderTransfer = $hydrateOrderPlugin->hydrate($orderTransfer);
        }

        return $orderTransfer;
    }
}

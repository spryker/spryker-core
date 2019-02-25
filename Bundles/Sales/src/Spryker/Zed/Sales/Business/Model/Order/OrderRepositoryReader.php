<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderRepositoryReader implements OrderRepositoryReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface $orderHydratorStrategyResolver
     */
    protected $orderHydratorStrategyResolver;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @param \Spryker\Zed\Sales\Business\StrategyResolver\OrderHydratorStrategyResolverInterface $orderHydratorStrategyResolver
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(
        OrderHydratorStrategyResolverInterface $orderHydratorStrategyResolver,
        SalesRepositoryInterface $salesRepository
    ) {
        $this->orderHydratorStrategyResolver = $orderHydratorStrategyResolver;
        $this->salesRepository = $salesRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrderByOrderReference(OrderTransfer $orderTransfer): OrderTransfer
    {
        $idSalesOrder = $this->salesRepository->findCustomerOrderIdByOrderReference(
            $orderTransfer->requireCustomerReference()->getCustomerReference(),
            $orderTransfer->requireOrderReference()->getOrderReference()
        );

        if ($idSalesOrder === null) {
            return new OrderTransfer();
        }

        return $this->orderHydratorStrategyResolver
            ->resolve($orderTransfer->getItems())
            ->hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder);
    }
}

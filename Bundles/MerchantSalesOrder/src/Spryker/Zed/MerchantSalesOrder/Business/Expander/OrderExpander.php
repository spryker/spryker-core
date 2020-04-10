<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expander;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface
     */
    protected $merchantSalesOrderRepository;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
     */
    public function __construct(MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository)
    {
        $this->merchantSalesOrderRepository = $merchantSalesOrderRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithMerchantOrderData(OrderTransfer $orderTransfer): OrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setWithItems(true)
            ->setIdOrder($orderTransfer->getIdSalesOrder());

        $merchantOrderCollectionTransfer = $this->merchantSalesOrderRepository->getMerchantOrderCollection(
            $merchantOrderCriteriaTransfer
        );

        $orderTransfer = $this->expandOrderItemsWithMerchantOrderReference($orderTransfer, $merchantOrderCollectionTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItemsWithMerchantOrderReference(
        OrderTransfer $orderTransfer,
        MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
    ): OrderTransfer {
        $groupedByItemIdMerchantOrderTransfers = [];

        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
                $groupedByItemIdMerchantOrderTransfers[$merchantOrderItemTransfer->getIdOrderItem()] = $merchantOrderTransfer;
            }
        }

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!isset($groupedByItemIdMerchantOrderTransfers[$itemTransfer->getIdSalesOrderItem()])) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrder */
            $merchantOrder = $groupedByItemIdMerchantOrderTransfers[$itemTransfer->getIdSalesOrderItem()];
            $itemTransfer->setMerchantOrderReference($merchantOrder->getMerchantOrderReference());
        }

        return $orderTransfer;
    }
}

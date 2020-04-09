<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Expander;

use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
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
        $merchantOrderItemCriteriaTransfer = (new MerchantOrderItemCriteriaTransfer())->setWithOrder(true);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $merchantOrderItemCriteriaTransfer->addIdOrderItem($itemTransfer->getIdSalesOrderItem());
        }

        if (!$merchantOrderItemCriteriaTransfer->getIdOrderItems()) {
            return $orderTransfer;
        }

        $merchantOrderItemCollectionTransfer = $this->merchantSalesOrderRepository->getMerchantOrderItemCollection(
            $merchantOrderItemCriteriaTransfer
        );

        $orderTransfer = $this->expandOrderItemsWithMerchantOrderReference($orderTransfer, $merchantOrderItemCollectionTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItemsWithMerchantOrderReference(
        OrderTransfer $orderTransfer,
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
    ): OrderTransfer {
        $groupedByItemIdMerchantOrderItemTransfers = [];
        foreach ($merchantOrderItemCollectionTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $groupedByItemIdMerchantOrderItemTransfers[$merchantOrderItemTransfer->getIdOrderItem()] = $merchantOrderItemTransfer;
        }

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            /** @var \Generated\Shared\Transfer\MerchantOrderItemTransfer|null $merchantOrderItemTransfer */
            $merchantOrderItemTransfer = $groupedByItemIdMerchantOrderItemTransfers[$itemTransfer->getIdSalesOrderItem()] ?? null;
            if (!$merchantOrderItemTransfer) {
                continue;
            }

            $itemTransfer->setMerchantOrderReference($merchantOrderItemTransfer->getMerchantOrder()->getMerchantOrderReference());
        }

        return $orderTransfer;
    }
}

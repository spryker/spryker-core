<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class OrderItemProductClassExpander implements OrderItemProductClassExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(protected SelfServicePortalRepositoryInterface $selfServicePortalRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithProductClasses(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($orderTransfer);

        if (!$salesOrderItemIds) {
            return $orderTransfer;
        }

        $productClassesBySalesOrderItemId = $this->selfServicePortalRepository->getProductClassesGroupedBySalesOrderItemIds($salesOrderItemIds);

        if (!$productClassesBySalesOrderItemId) {
            return $orderTransfer;
        }

        return $this->expandOrderItemTransfersWithProductClasses($orderTransfer, $productClassesBySalesOrderItemId);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int>
     */
    protected function extractSalesOrderItemIds(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>> $productClassesBySalesOrderItemId
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItemTransfersWithProductClasses(
        OrderTransfer $orderTransfer,
        array $productClassesBySalesOrderItemId
    ): OrderTransfer {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

            if (!isset($productClassesBySalesOrderItemId[$idSalesOrderItem])) {
                continue;
            }

            $itemTransfer->setProductClasses(new ArrayObject($productClassesBySalesOrderItemId[$idSalesOrderItem]));
        }

        return $orderTransfer;
    }
}

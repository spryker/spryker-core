<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface;

class OrderItemProductTypeExpander implements OrderItemProductTypeExpanderInterface
{
    /**
     * @var \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface
     */
    protected $sspServiceManagementRepository;

    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface $sspServiceManagementRepository
     */
    public function __construct(SspServiceManagementRepositoryInterface $sspServiceManagementRepository)
    {
        $this->sspServiceManagementRepository = $sspServiceManagementRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithProductTypes(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($orderTransfer);

        if (!$salesOrderItemIds) {
            return $orderTransfer;
        }

        $productTypesBySalesOrderItemId = $this->sspServiceManagementRepository
            ->getProductTypesGroupedBySalesOrderItemIds($salesOrderItemIds);

        if (!$productTypesBySalesOrderItemId) {
            return $orderTransfer;
        }

        return $this->expandOrderItemTransfersWithProductTypes($orderTransfer, $productTypesBySalesOrderItemId);
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
            if ($itemTransfer->getIdSalesOrderItem()) {
                $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItemOrFail();
            }
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int, array<string>> $productTypesBySalesOrderItemId
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItemTransfersWithProductTypes(
        OrderTransfer $orderTransfer,
        array $productTypesBySalesOrderItemId
    ): OrderTransfer {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

            if (!$idSalesOrderItem || !isset($productTypesBySalesOrderItemId[$idSalesOrderItem])) {
                continue;
            }

            $itemTransfer->setProductTypes($productTypesBySalesOrderItemId[$idSalesOrderItem]);
        }

        return $orderTransfer;
    }
}

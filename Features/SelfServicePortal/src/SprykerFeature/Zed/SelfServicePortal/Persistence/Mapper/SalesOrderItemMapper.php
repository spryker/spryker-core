<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

class SalesOrderItemMapper
{
    public function mapSalesOrderItemEntityToItemTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        return $itemTransfer
            ->setIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem())
            ->setSku($salesOrderItemEntity->getSku());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function mapSalesOrderItemEntitiesToItemTransfers(ObjectCollection $salesOrderItemEntities): array
    {
        $itemTransfers = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $itemTransfers[] = $this->mapSalesOrderItemEntityToItemTransfer(
                $salesOrderItemEntity,
                new ItemTransfer(),
            );
        }

        return $itemTransfers;
    }
}

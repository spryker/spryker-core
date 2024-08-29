<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Propel\Runtime\Collection\Collection;

interface OrderItemMapperInterface
{
    /**
     * @param array $orderItemsMatrixResult
     *
     * @return array
     */
    public function mapOrderItemMatrix(array $orderItemsMatrixResult): array;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory> $omsOrderItemStateHistoryEntities
     *
     * @return array<\Generated\Shared\Transfer\ItemStateTransfer>
     */
    public function mapOmsOrderItemStateHistoryEntityCollectionToItemStateHistoryTransfers(
        Collection $omsOrderItemStateHistoryEntities
    ): array;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function mapSalesOrderItemEntityCollectionToOrderItemTransfers(
        Collection $salesOrderItemEntityCollection
    ): array;

    /**
     * @param array<array<string|int>> $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function mapSalesOrderItemEntitiesToOrderMatrixCollectionTransfer(
        array $orderItemEntities,
        OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer
    ): OrderMatrixCollectionTransfer;
}

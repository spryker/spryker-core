<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Propel\Runtime\Collection\ObjectCollection;

interface OrderItemMapperInterface
{
    /**
     * @param array $orderItemsMatrixResult
     *
     * @return array
     */
    public function mapOrderItemMatrix(array $orderItemsMatrixResult): array;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory[] $omsOrderItemStateHistoryEntities
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer[]
     */
    public function mapOmsOrderItemStateHistoryEntityCollectionToItemStateHistoryTransfers(
        ObjectCollection $omsOrderItemStateHistoryEntities
    ): array;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntityCollection
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function mapSalesOrderItemEntityCollectionToOrderItemTransfers(
        ObjectCollection $salesOrderItemEntityCollection
    ): array;
}

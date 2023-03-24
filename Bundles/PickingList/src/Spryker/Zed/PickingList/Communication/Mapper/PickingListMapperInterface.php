<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface PickingListMapperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer
     */
    public function mapItemTransfersToGeneratePickingListsRequestTransfer(
        ArrayObject $itemTransfers,
        GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
    ): GeneratePickingListsRequestTransfer;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderItemEntityToOrderTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        OrderTransfer $orderTransfer
    ): OrderTransfer;
}

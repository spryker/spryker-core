<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;

interface SalesOrdersResourceMapperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     * @param \Generated\Shared\Transfer\OrderResourceCollectionTransfer $orderResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function mapOrdersBackendApiAttributesTransfersToOrderResourceCollectionTransfer(
        array $ordersBackendApiAttributesTransfers,
        OrderResourceCollectionTransfer $orderResourceCollectionTransfer
    ): OrderResourceCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    public function mapOrderListTransferToOrdersBackendApiAttributesTransfers(OrderListTransfer $orderListTransfer): array;
}

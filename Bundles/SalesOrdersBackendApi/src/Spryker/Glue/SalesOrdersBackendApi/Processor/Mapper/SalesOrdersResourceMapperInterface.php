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
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     * @param \Generated\Shared\Transfer\OrderResourceCollectionTransfer $orderResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function mapApiOrdersAttributesTransfersToOrderResourceCollectionTransfer(
        array $apiOrdersAttributesTransfers,
        OrderResourceCollectionTransfer $orderResourceCollectionTransfer
    ): OrderResourceCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer>
     */
    public function mapOrderListTransferToApiOrdersAttributesTransfers(OrderListTransfer $orderListTransfer): array;
}

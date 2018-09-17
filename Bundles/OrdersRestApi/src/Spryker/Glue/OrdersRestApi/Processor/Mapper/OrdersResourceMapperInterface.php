<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrdersRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrdersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\OrderItemsTransfer[] $orderItemTransfers
     *
     * @return \Generated\Shared\Transfer\OrdersRestAttributesTransfer
     */
    public function mapOrderToOrdersRestAttributes(OrderTransfer $orderTransfer, array $orderItemTransfers): OrdersRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $orderItems
     *
     * @return \Generated\Shared\Transfer\OrderItemsTransfer[]
     */
    public function mapTransformedBundleItems(OrderTransfer $orderTransfer, array $orderItems): array;
}

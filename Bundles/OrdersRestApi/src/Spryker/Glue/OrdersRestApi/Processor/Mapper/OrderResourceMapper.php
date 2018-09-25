<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderDetailsRestAttributesTransfer;
use Generated\Shared\Transfer\OrderRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrderResourceMapper implements OrderResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderRestAttributesTransfer
     */
    public function mapOrderTransferToOrderRestAttributesTransfer(OrderTransfer $orderTransfer): OrderRestAttributesTransfer
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireTaxTotal();

        $orderRestAttributesTransfer = (new OrderRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $orderRestAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        return $orderRestAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderDetailsRestAttributesTransfer
     */
    public function mapOrderTransferToOrderDetailsRestAttributesTransfer(OrderTransfer $orderTransfer): OrderDetailsRestAttributesTransfer
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireTaxTotal();

        $orderDetailsRestAttributesTransfer = (new OrderDetailsRestAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $orderDetailsRestAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        return $orderDetailsRestAttributesTransfer;
    }
}

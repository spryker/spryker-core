<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapQuoteTransferToOrderTransfer(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $itemTransfers = $quoteTransfer->getItems();
        $quoteTransfer->setItems(new ArrayObject());

        $orderTransfer->fromArray($quoteTransfer->getCustomerOrFail()->toArray(), true);
        $orderTransfer->fromArray($quoteTransfer->toArray(), true)
            ->setStore($quoteTransfer->getStoreOrFail()->getName())
            ->setItems($itemTransfers);

        $quoteTransfer->setItems($itemTransfers);

        return $orderTransfer;
    }
}

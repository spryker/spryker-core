<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaver as SalesOrderSaverWithoutItemShippingAddress;

class SalesOrderSaver extends SalesOrderSaverWithoutItemShippingAddress
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateAddresses(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getBillingAddress());
        $salesOrderEntity->setBillingAddress($billingAddressEntity);
    }
}

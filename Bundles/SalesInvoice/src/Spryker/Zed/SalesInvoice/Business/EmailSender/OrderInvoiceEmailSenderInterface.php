<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Business\EmailSender;

use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer;

interface OrderInvoiceEmailSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceSendResponseTransfer
     */
    public function sendOrderInvoices(OrderInvoiceSendRequestTransfer $orderInvoiceSendRequestTransfer): OrderInvoiceSendResponseTransfer;
}

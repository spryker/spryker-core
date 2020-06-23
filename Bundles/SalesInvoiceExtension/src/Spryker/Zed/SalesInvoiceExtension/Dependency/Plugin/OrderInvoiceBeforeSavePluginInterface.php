<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * todo
 */
interface OrderInvoiceBeforeSavePluginInterface
{
    /**
     * Specification:
     * - todo
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function execute(OrderInvoiceTransfer $orderInvoiceTransfer, OrderTransfer $orderTransfer): OrderInvoiceTransfer;
}

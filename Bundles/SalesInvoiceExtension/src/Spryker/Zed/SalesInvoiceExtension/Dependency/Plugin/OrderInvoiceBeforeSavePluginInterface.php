<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Provides capabilities to expand order invoice before storing it into the persistence.
 *
 * Order transfer has limited amount of data.
 * Order transfer changes will be ignored.
 */
interface OrderInvoiceBeforeSavePluginInterface
{
    /**
     * Specification:
     * - Executed before order invoice stored into persistence.
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

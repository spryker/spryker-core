<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice;

use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoiceBeforeSavePluginInterface;

class OrderInvoiceBeforeSavePluginMock implements OrderInvoiceBeforeSavePluginInterface
{
    public const FAKE_REFERENCE_FOR_PLUGIN_CHECK = 'FAKE_REFERENCE_FOR_PLUGIN_CHECK';

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer $orderInvoiceTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function execute(OrderInvoiceTransfer $orderInvoiceTransfer, OrderTransfer $orderTransfer): OrderInvoiceTransfer
    {
        return $orderInvoiceTransfer->setReference(static::FAKE_REFERENCE_FOR_PLUGIN_CHECK);
    }
}

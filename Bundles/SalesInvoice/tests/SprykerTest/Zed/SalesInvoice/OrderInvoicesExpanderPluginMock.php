<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice;

use Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin\OrderInvoicesExpanderPluginInterface;

class OrderInvoicesExpanderPluginMock implements OrderInvoicesExpanderPluginInterface
{
    public const FAKE_REFERENCE_FOR_PLUGIN_CHECK = 'FAKE_REFERENCE_FOR_PLUGIN_CHECK';

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceTransfer[] $orderInvoiceTransfers
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer[]
     */
    public function expand(array $orderInvoiceTransfers): array
    {
        foreach ($orderInvoiceTransfers as $orderInvoiceTransfer) {
            $orderInvoiceTransfer->setReference(static::FAKE_REFERENCE_FOR_PLUGIN_CHECK);
        }

        return $orderInvoiceTransfers;
    }
}

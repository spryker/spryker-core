<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesInvoice;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SalesInvoiceConfig extends AbstractSharedConfig
{
    protected const ORDER_INVOICE_SEQUENCE_DEFAULT = 'Invoice';

    /**
     * @api
     *
     * @return string
     */
    public function getOrderInvoiceSequence(): string
    {
        return (string)$this->get(SalesInvoiceConstants::ORDER_INVOICE_SEQUENCE, static::ORDER_INVOICE_SEQUENCE_DEFAULT);
    }
}

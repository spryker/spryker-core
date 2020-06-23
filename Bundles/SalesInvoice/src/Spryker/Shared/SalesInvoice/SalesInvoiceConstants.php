<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SalesInvoice;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SalesInvoiceConstants
{
    /**
     * Specification:
     * - Store specific order invoice prefix for reference generation.
     *
     * @api
     */
    public const ORDER_INVOICE_PREFIX = 'SALES_INVOICE:ORDER_INVOICE_PREFIX';
}

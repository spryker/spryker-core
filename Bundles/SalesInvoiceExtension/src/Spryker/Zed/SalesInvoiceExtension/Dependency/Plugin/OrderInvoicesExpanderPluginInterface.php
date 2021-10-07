<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoiceExtension\Dependency\Plugin;

/**
 * Provides capabilities to expand order invoice after getting it from the persistence.
 */
interface OrderInvoicesExpanderPluginInterface
{
    /**
     * Specification:
     * - Executed after order invoice loaded from persistence.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderInvoiceTransfer> $orderInvoiceTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderInvoiceTransfer>
     */
    public function expand(array $orderInvoiceTransfers): array;
}

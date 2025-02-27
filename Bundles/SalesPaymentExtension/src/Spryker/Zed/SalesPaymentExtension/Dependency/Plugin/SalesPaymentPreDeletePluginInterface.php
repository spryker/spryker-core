<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;

/**
 * Implement this plugin interface to add logic before a sales payment is deleted.
 */
interface SalesPaymentPreDeletePluginInterface
{
    /**
     * Specification:
     * - Executed before a sales payment is deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer
     *
     * @return void
     */
    public function preDelete(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void;
}

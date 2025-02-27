<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * Implement this plugin interface to add logic before a sales order item is updated.
 */
interface SalesOrderItemsPreUpdatePluginInterface
{
    /**
     * Specification:
     * - Executed before a sales order item is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preUpdate(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): QuoteTransfer;
}

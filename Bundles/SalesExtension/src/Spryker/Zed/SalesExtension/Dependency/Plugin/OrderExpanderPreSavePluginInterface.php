<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;

/**
 * Use this plugin to expand SpySalesOrderEntityTransfer before saving to DB.
 */
interface OrderExpanderPreSavePluginInterface
{
    /**
     * Specification:
     *   - A plugin which hydrates SpySalesOrderEntityTransfer before order created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expand(SpySalesOrderEntityTransfer $salesOrderEntityTransfer, QuoteTransfer $quoteTransfer): SpySalesOrderEntityTransfer;
}

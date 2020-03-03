<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface CompanySalesConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expands sales order with company uuid and persists updated entity.
     * - Requires SaveOrderTransfer::idSalesOrder to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateOrderCompanyUuid(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): void;
}

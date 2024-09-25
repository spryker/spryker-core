<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface SalesDiscountConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expects `QuoteTransfer.customer` to be set.
     * - Expects `QuoteTransfer.customer.idCustomer` to be set.
     * - Checks if customer's order count matches clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCustomerOrderCountSatisfiedBy(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): bool;
}

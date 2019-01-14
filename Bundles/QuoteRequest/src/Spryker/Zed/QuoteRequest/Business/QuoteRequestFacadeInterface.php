<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteRequestFacadeInterface
{
    /**
     * Specification:
     * - Creates new quote request from QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequestFromQuote(QuoteTransfer $quoteTransfer): QuoteRequestTransfer;

    /**
     * Specification:
     *  - Retrieves quote request collection by customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getCustomerQuoteRequestCollection(CustomerTransfer $customerTransfer): QuoteRequestCollectionTransfer;
}

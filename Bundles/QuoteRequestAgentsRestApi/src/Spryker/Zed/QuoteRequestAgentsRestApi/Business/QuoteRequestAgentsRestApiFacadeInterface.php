<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgentsRestApi\Business;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

/**
 * @method \Spryker\Zed\QuoteRequestAgentsRestApi\Business\QuoteRequestAgentsRestApiBusinessFactory getFactory()
 */
interface QuoteRequestAgentsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Requires `QuoteRequestTransfer.latestVersion`, `QuoteRequestTransfer.companyUser`, `QuoteRequestTransfer.companyUser.customer`,
     *   `QuoteRequestTransfer.latestVersion.quote`, `QuoteRequestTransfer.latestVersion.quote.uuid` transfer properties to be set.
     * - Finds a Request for Quote by `QuoteRequestTransfer.companyUser.customer` and `QuoteRequestTransfer.latestVersion.quote.uuid`.
     * - Updates quote request data.
     * - Returns `QuoteRequestResponseTransfer` with errors in case if `QuoteRequestTransfer` is not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;
}

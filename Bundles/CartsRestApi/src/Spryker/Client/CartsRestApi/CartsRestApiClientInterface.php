<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartsRestApiClientInterface
{
    /**
     * Specification:
     * - Finds customer quote by uuid.
     * - Uuid and customerReference must be set in the QuoteTransfer taken as parameter.
     * - Checks that customer is authorized to access a quote by the given uuid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findCustomerQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}

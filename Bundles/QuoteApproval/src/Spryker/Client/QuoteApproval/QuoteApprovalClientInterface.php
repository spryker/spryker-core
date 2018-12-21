<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalClientInterface
{
    /**
     * Specification:
     * - Calculates approval status for quote
     * - Returns status `Approved` if at least one approval request has status `Approved`.
     * - Returns status `Waiting` if at least one approval request in status `Waiting` and all other are `Desclined`.
     * - Returns status `Desclined` if all all approval requests are declined.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteStatus(QuoteTransfer $quoteTransfer): ?string;
}

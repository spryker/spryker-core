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
     * - Has to be implemented in story PS-4362.
     * - Todo: update return type according to latest changes in PS-4362.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteStatus(QuoteTransfer $quoteTransfer): ?string;
}

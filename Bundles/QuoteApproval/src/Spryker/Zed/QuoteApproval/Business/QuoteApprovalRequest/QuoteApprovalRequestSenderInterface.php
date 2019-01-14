<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest;

use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteApprovalRequestSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function sendQuoteApproveRequest(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): QuoteResponseTransfer;
}

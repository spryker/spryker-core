<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Model;

use Generated\Shared\Transfer\QuoteApproveRequestTransfer;

interface QuoteApprovalRequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return bool
     */
    public function isApproveRequestValid(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): bool;
}

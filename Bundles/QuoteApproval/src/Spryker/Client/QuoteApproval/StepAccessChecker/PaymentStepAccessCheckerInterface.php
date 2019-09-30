<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\StepAccessChecker;

use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentStepAccessCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkPaymentStepAccessibility(QuoteTransfer $quoteTransfer): bool;
}

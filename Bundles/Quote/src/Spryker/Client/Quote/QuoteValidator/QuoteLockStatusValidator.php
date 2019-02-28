<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\QuoteValidator;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteLockStatusValidator implements QuoteLockStatusValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return (bool)$quoteTransfer->getIsLocked();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\QuoteEditStatus;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\Quote\QuoteLock\QuoteLockStatusCheckerInterface;

class QuoteEditStatusChecker implements QuoteEditStatusCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\Quote\QuoteLock\QuoteLockStatusCheckerInterface
     */
    protected $quoteLockStatusChecker;

    /**
     * @param \Spryker\Client\Quote\QuoteLock\QuoteLockStatusCheckerInterface $quoteLockStatusChecker
     */
    public function __construct(QuoteLockStatusCheckerInterface $quoteLockStatusChecker)
    {
        $this->quoteLockStatusChecker = $quoteLockStatusChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->quoteLockStatusChecker->isQuoteLocked($quoteTransfer)) {
            return false;
        }

        if ($quoteTransfer->getIdQuote() === null) {
            return true;
        }

        return $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\QuoteValidator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;

class QuoteEditStatusValidator implements QuoteEditStatusValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface
     */
    protected $quoteLockStatusValidator;

    /**
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface $quoteLockStatusValidator
     */
    public function __construct(QuoteLockStatusValidatorInterface $quoteLockStatusValidator)
    {
        $this->quoteLockStatusValidator = $quoteLockStatusValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->quoteLockStatusValidator->isQuoteLocked($quoteTransfer)) {
            return false;
        }

        if (!$quoteTransfer->getIdQuote()) {
            return true;
        }

        if ($this->isQuoteOwner($quoteTransfer)) {
            return true;
        }

        return $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getCustomerReference() === $quoteTransfer->requireCustomer()->getCustomer()
            ->getCustomerReference();
    }
}

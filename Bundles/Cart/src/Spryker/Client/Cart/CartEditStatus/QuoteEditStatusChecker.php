<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\CartEditStatus;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Kernel\PermissionAwareTrait;

class QuoteEditStatusChecker implements QuoteEditStatusCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface $quoteClient
     */
    public function __construct(CartToQuoteInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->quoteClient->isQuoteLocked($quoteTransfer)) {
            return false;
        }

        if ($quoteTransfer->getIdQuote() === null) {
            return true;
        }

        return $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote());
    }
}

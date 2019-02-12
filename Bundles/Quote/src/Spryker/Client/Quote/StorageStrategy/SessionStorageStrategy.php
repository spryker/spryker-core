<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteLock\QuoteLockStatusCheckerInterface;
use Spryker\Client\Quote\Session\QuoteSessionInterface;
use Spryker\Shared\Quote\QuoteConfig;

class SessionStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Client\Quote\Session\QuoteSessionInterface
     */
    protected $quoteSession;

    /**
     * @var \Spryker\Client\Quote\QuoteLock\QuoteLockStatusCheckerInterface
     */
    protected $quoteLockStatusChecker;

    /**
     * @param \Spryker\Client\Quote\Session\QuoteSessionInterface $quoteSession
     * @param \Spryker\Client\Quote\QuoteLock\QuoteLockStatusCheckerInterface $quoteLockStatusChecker
     */
    public function __construct(QuoteSessionInterface $quoteSession, QuoteLockStatusCheckerInterface $quoteLockStatusChecker)
    {
        $this->quoteSession = $quoteSession;
        $this->quoteLockStatusChecker = $quoteLockStatusChecker;
    }

    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_SESSION;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->quoteSession->getQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->quoteSession->setQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function clearQuote()
    {
        $this->quoteSession->clearQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteLockStatusChecker->isQuoteLocked($quoteTransfer);
    }
}

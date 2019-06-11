<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface;
use Spryker\Client\Quote\Session\QuoteSessionInterface;
use Spryker\Shared\Quote\QuoteConfig;

class SessionStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Client\Quote\Session\QuoteSessionInterface
     */
    protected $quoteSession;

    /**
     * @var \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface
     */
    protected $quoteLockStatusValidator;

    /**
     * @var \Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface
     */
    protected $quoteEditStatusValidator;

    /**
     * @var \Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface
     */
    protected $quoteLocker;

    /**
     * @param \Spryker\Client\Quote\Session\QuoteSessionInterface $quoteSession
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface $quoteLockStatusValidator
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface $quoteEditStatusValidator
     * @param \Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface $quoteLocker
     */
    public function __construct(
        QuoteSessionInterface $quoteSession,
        QuoteLockStatusValidatorInterface $quoteLockStatusValidator,
        QuoteEditStatusValidatorInterface $quoteEditStatusValidator,
        QuoteLockerInterface $quoteLocker
    ) {
        $this->quoteSession = $quoteSession;
        $this->quoteLockStatusValidator = $quoteLockStatusValidator;
        $this->quoteEditStatusValidator = $quoteEditStatusValidator;
        $this->quoteLocker = $quoteLocker;
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
        return $this->quoteLockStatusValidator->isQuoteLocked($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteEditStatusValidator->isQuoteEditable($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->quoteLocker->lock($quoteTransfer);
    }
}

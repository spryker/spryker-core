<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface;
use Spryker\Client\Quote\Session\QuoteSessionInterface;
use Spryker\Client\Quote\Zed\QuoteStubInterface;
use Spryker\Shared\Quote\QuoteConfig;

class DatabaseStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\Quote\Zed\QuoteStubInterface
     */
    protected $quoteStub;

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
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface $customerClient
     * @param \Spryker\Client\Quote\Zed\QuoteStubInterface $quoteStub
     * @param \Spryker\Client\Quote\Session\QuoteSessionInterface $quoteSession
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface $quoteLockStatusValidator
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface $quoteEditStatusValidator
     */
    public function __construct(
        QuoteToCustomerClientInterface $customerClient,
        QuoteStubInterface $quoteStub,
        QuoteSessionInterface $quoteSession,
        QuoteLockStatusValidatorInterface $quoteLockStatusValidator,
        QuoteEditStatusValidatorInterface $quoteEditStatusValidator
    ) {
        $this->customerClient = $customerClient;
        $this->quoteStub = $quoteStub;
        $this->quoteSession = $quoteSession;
        $this->quoteLockStatusValidator = $quoteLockStatusValidator;
        $this->quoteEditStatusValidator = $quoteEditStatusValidator;
    }

    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->customerClient->isLoggedIn();
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteLockStatusValidator->isQuoteLocked($quoteTransfer);
    }

    /**
     * @return $this
     */
    public function clearQuote()
    {
        $quoteResponseTransfer = $this->quoteStub->deleteQuote($this->getQuote());
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteSession->clearQuote();
        }

        return $this;
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
}

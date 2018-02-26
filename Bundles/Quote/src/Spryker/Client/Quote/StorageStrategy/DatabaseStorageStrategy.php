<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface;
use Spryker\Client\Quote\Session\QuoteSession;
use Spryker\Client\Quote\Zed\QuoteStubInterface;
use Spryker\Shared\Quote\QuoteConfig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface $customerClient
     * @param \Spryker\Client\Quote\Zed\QuoteStubInterface $quoteStub
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(
        QuoteToCustomerClientInterface $customerClient,
        QuoteStubInterface $quoteStub,
        SessionInterface $session
    ) {
        $this->customerClient = $customerClient;
        $this->quoteStub = $quoteStub;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getStorageType()
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
        $quoteTransfer = new QuoteTransfer();
        $quoteResponseTransfer = $this->quoteStub->getQuoteByCustomer($this->customerClient->getCustomer());
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return $this
     */
    public function saveQuote(QuoteTransfer $quoteTransfer)
    {
        $this->setCustomer($quoteTransfer);
        $quoteResponseTransfer = $this->quoteStub->persistQuote($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->saveQuoteToSession($quoteResponseTransfer->getQuoteTransfer());
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return $this
     */
    public function clearQuote(QuoteTransfer $quoteTransfer)
    {
        $quoteResponseTransfer = $this->quoteStub->deleteQuote($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->saveQuoteToSession(new QuoteTransfer());
        }
        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveQuoteToSession(QuoteTransfer $quoteTransfer)
    {
        $this->session->set(QuoteSession::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setCustomer(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->setCustomer($this->customerClient->getCustomer());
    }
}

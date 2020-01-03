<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface;
use Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface;
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
     * @var \Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface
     */
    protected $quoteLocker;

    /**
     * @var \Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyPreCheckPluginInterface[]
     */
    protected $databaseStrategyPreCheckPlugins;

    /**
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface $customerClient
     * @param \Spryker\Client\Quote\Zed\QuoteStubInterface $quoteStub
     * @param \Spryker\Client\Quote\Session\QuoteSessionInterface $quoteSession
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface $quoteLockStatusValidator
     * @param \Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface $quoteEditStatusValidator
     * @param \Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface $quoteLocker
     * @param \Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyPreCheckPluginInterface[] $databaseStrategyPreCheckPlugins
     */
    public function __construct(
        QuoteToCustomerClientInterface $customerClient,
        QuoteStubInterface $quoteStub,
        QuoteSessionInterface $quoteSession,
        QuoteLockStatusValidatorInterface $quoteLockStatusValidator,
        QuoteEditStatusValidatorInterface $quoteEditStatusValidator,
        QuoteLockerInterface $quoteLocker,
        array $databaseStrategyPreCheckPlugins
    ) {
        $this->customerClient = $customerClient;
        $this->quoteStub = $quoteStub;
        $this->quoteSession = $quoteSession;
        $this->quoteLockStatusValidator = $quoteLockStatusValidator;
        $this->quoteEditStatusValidator = $quoteEditStatusValidator;
        $this->quoteLocker = $quoteLocker;
        $this->databaseStrategyPreCheckPlugins = $databaseStrategyPreCheckPlugins;
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
        if (!$this->executeDatabaseStrategyPreCheckPlugins()) {
            return false;
        }

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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->quoteLocker->lock($quoteTransfer);
    }

    /**
     * @return bool
     */
    protected function executeDatabaseStrategyPreCheckPlugins(): bool
    {
        $quoteTransfer = $this->getQuote();

        foreach ($this->databaseStrategyPreCheckPlugins as $databaseStrategyPreCheckPlugin) {
            if (!$databaseStrategyPreCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}

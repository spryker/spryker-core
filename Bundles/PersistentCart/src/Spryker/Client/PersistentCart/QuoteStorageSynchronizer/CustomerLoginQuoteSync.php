<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteStorageSynchronizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;

class CustomerLoginQuoteSync implements CustomerLoginQuoteSyncInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    protected $persistentCartStub;

    /**
     * @var \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface
     */
    protected $quoteUpdatePluginExecutor;

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface $persistentCartStub
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface $customerClient
     */
    public function __construct(
        PersistentCartStubInterface $persistentCartStub,
        PersistentCartToQuoteClientInterface $quoteClient,
        QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor,
        PersistentCartToZedRequestClientInterface $zedRequestClient,
        PersistentCartToCustomerClientInterface $customerClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartStub = $persistentCartStub;
        $this->quoteUpdatePluginExecutor = $quoteUpdatePluginExecutor;
        $this->zedRequestClient = $zedRequestClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function syncQuoteForCustomer(CustomerTransfer $customerTransfer): void
    {
        if ($this->isDatabaseStorageStrategy($this->quoteClient->getStorageStrategy())) {
            return;
        }

        $quoteTransfer = $this->quoteClient->getQuote();
        if ($quoteTransfer->getCustomer() || $quoteTransfer->getCustomerReference()) {
            return;
        }

        $quoteResponseTransfer = $this->getQuoteResponseTransfer($quoteTransfer, $customerTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return;
        }

        $this->executeQuote($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function syncQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->isQuoteNotSynchronizable($quoteTransfer)) {
            return $quoteTransfer;
        }

        $quoteResponseTransfer = $this->getQuoteResponseTransfer($quoteTransfer, $this->customerClient->getCustomer());
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteTransfer;
        }

        $this->executeQuote($quoteResponseTransfer);

        return $quoteResponseTransfer->getQuoteTransfer();
    }

    /**
     * @param string $strategyName
     *
     * @return bool
     */
    protected function isDatabaseStorageStrategy(string $strategyName): bool
    {
        return $strategyName !== static::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteNotSynchronizable(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getIdQuote()
            || $quoteTransfer->getItems()->count()
            || $this->isDatabaseStorageStrategy($this->quoteClient->getStorageStrategy());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function getQuoteResponseTransfer(
        QuoteTransfer $quoteTransfer,
        CustomerTransfer $customerTransfer
    ): QuoteResponseTransfer {
        $quoteSyncRequestTransfer = new QuoteSyncRequestTransfer();
        $quoteSyncRequestTransfer->setQuoteTransfer($quoteTransfer);
        $quoteSyncRequestTransfer->setCustomerTransfer($customerTransfer);

        return $this->persistentCartStub->syncStorageQuote($quoteSyncRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function executeQuote(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
        $this->quoteUpdatePluginExecutor->executePlugins($quoteResponseTransfer);
    }
}

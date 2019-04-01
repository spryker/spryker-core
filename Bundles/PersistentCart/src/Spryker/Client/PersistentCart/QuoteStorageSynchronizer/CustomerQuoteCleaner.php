<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteStorageSynchronizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;
use Spryker\Shared\Quote\QuoteConfig;

class CustomerQuoteCleaner implements CustomerQuoteCleanerInterface
{
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
     * @param \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface $persistentCartStub
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(
        PersistentCartStubInterface $persistentCartStub,
        PersistentCartToQuoteClientInterface $quoteClient,
        QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor,
        PersistentCartToZedRequestClientInterface $zedRequestClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartStub = $persistentCartStub;
        $this->quoteUpdatePluginExecutor = $quoteUpdatePluginExecutor;
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reloadQuoteForCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->quoteClient->setQuote(new QuoteTransfer());

        if ($this->quoteClient->getStorageStrategy() !== QuoteConfig::STORAGE_STRATEGY_DATABASE) {
            return;
        }

        $quoteSyncRequestTransfer = (new QuoteSyncRequestTransfer())
            ->setQuoteTransfer(new QuoteTransfer())
            ->setCustomerTransfer($customerTransfer);

        $quoteResponseTransfer = $this->persistentCartStub->syncStorageQuote($quoteSyncRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return;
        }

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $this->quoteUpdatePluginExecutor->executePlugins($quoteResponseTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteWriter;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class QuoteDeleter implements QuoteDeleterInterface
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
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * QuoteDeleter constructor.
     *
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface $customerClient
     * @param \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface $persistentCartStub
     * @param \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor
     */
    public function __construct(
        PersistentCartToQuoteClientInterface $quoteClient,
        ZedRequestClientInterface $zedRequestClient,
        PersistentCartToCustomerClientInterface $customerClient,
        PersistentCartStubInterface $persistentCartStub,
        QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartStub = $persistentCartStub;
        $this->quoteUpdatePluginExecutor = $quoteUpdatePluginExecutor;
        $this->zedRequestClient = $zedRequestClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer($this->customerClient->getCustomer());
        $quoteResponseTransfer = $this->persistentCartStub->deleteQuote($quoteTransfer);
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
        if ($this->quoteClient->getQuote()->getIdQuote() === $quoteTransfer->getIdQuote()) {
            $this->quoteClient->setQuote(new QuoteTransfer());
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeUpdateQuotePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->quoteUpdatePluginExecutor->executePlugins($quoteResponseTransfer);
    }
}

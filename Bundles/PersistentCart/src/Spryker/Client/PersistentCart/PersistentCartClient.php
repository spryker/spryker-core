<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class PersistentCartClient extends AbstractClient implements PersistentCartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $persistentCartStub = $this->getFactory()->createZedPersistentCartStub();
        $quoteResponseTransfer = $persistentCartStub->deleteQuote($quoteTransfer);
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function persistQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $persistentCartStub = $this->getFactory()->createZedPersistentCartStub();
        $quoteResponseTransfer = $persistentCartStub->persistQuote($quoteTransfer);
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->getFactory()->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeUpdateQuotePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()->createQuoteUpdatePluginExecutor()->executePlugins($quoteResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateCustomerQuote(QuoteTransfer $quoteTransfer)
    {
        $quoteClient = $this->getFactory()->getQuoteClient();
        if ($quoteClient->getQuote()->getIdQuote() === $quoteTransfer->getIdQuote()) {
            $quoteClient->setQuote($quoteTransfer);
        }
    }
}

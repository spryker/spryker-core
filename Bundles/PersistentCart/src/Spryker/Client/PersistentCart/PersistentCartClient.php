<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
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
        $quoteTransfer->setCustomer($this->getFactory()->getCustomerClient()->getCustomer());
        $quoteResponseTransfer = $persistentCartStub->deleteQuote($quoteTransfer);
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);
        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();

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
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $persistentCartStub = $this->getFactory()->createZedPersistentCartStub();
        $quoteResponseTransfer = $persistentCartStub->createQuote($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->getFactory()->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);
        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();

        return $quoteResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        $persistentCartStub = $this->getFactory()->createZedPersistentCartStub();
        $quoteResponseTransfer = $persistentCartStub->updateQuote($quoteUpdateRequestTransfer);
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
}

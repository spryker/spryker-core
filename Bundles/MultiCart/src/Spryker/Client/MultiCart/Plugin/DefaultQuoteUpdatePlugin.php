<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class DefaultQuoteUpdatePlugin extends AbstractPlugin implements QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - Extract Customer Default Quote Collection and saves it to customer session.
     * - Sets provided CustomerTransfer in newly created Quote when no other default Quote was found.
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if (!$quoteResponseTransfer->getCustomerQuotes()) {
            return $quoteResponseTransfer;
        }

        $defaultQuoteTransfer = $this->getDefaultQuote($quoteResponseTransfer);
        $defaultQuoteTransfer = $this->updateSessionQuote($defaultQuoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($defaultQuoteTransfer);
        $quoteResponseTransfer->setQuoteTransfer($defaultQuoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getDefaultQuote(QuoteResponseTransfer $quoteResponseTransfer): QuoteTransfer
    {
        $quoteCollectionTransfer = $quoteResponseTransfer->getCustomerQuotes();

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIsDefault()) {
                return $quoteTransfer;
            }
        }
        $quoteTransfer = new QuoteTransfer();
        if (count($quoteCollectionTransfer->getQuotes())) {
            $quoteTransfer = $quoteCollectionTransfer->getQuotes()->offsetGet(0);
        }
        $quoteTransfer->setIsDefault(true);
        $quoteTransfer->setCustomer($quoteResponseTransfer->getCustomer());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateSessionQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $sessionQuoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        if ($sessionQuoteTransfer->getIdQuote() === $quoteTransfer->getIdQuote()) {
            $quoteTransfer = $sessionQuoteTransfer->fromArray(
                $quoteTransfer->modifiedToArray(),
                true
            );
        }

        return $quoteTransfer;
    }
}

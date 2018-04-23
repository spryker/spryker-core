<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
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
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if ($quoteResponseTransfer->getCustomerQuotes()) {
            $defaultQuoteTransfer = $this->findDefaultQuote($quoteResponseTransfer->getCustomerQuotes());
            if ($defaultQuoteTransfer) {
                $defaultQuoteTransfer = $this->updateSessionQuote($defaultQuoteTransfer);
                $this->getFactory()->getQuoteClient()->setQuote($defaultQuoteTransfer);
                $quoteResponseTransfer->setQuoteTransfer($defaultQuoteTransfer);
                return $quoteResponseTransfer;
            }
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findDefaultQuote(QuoteCollectionTransfer $quoteCollectionTransfer): QuoteTransfer
    {
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

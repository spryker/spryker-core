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
use Spryker\Client\PersistentCart\Dependency\Plugin\QuoteUpdatePluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class DefaultQuoteUpdatePlugin extends AbstractPlugin implements QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - Extract Customer Quote Collection and saves it to customer session.
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if ($quoteResponseTransfer->getCustomerQuotes() && !$quoteResponseTransfer->getQuoteTransfer()->getIsDefault()) {
            $defaultQuoteTransfer = $this->findDefaultQuote($quoteResponseTransfer->getCustomerQuotes());
            if ($defaultQuoteTransfer) {
                $quoteResponseTransfer->setQuoteTransfer($defaultQuoteTransfer);
            }
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findDefaultQuote(QuoteCollectionTransfer $quoteCollectionTransfer): ?QuoteTransfer
    {
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIsDefault()) {
                return $quoteTransfer;
            }
        }

        return null;
    }
}

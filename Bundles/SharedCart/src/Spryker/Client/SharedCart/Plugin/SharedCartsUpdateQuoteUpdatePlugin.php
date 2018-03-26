<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCart\Dependency\Plugin\QuoteUpdatePluginInterface;

/**
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class SharedCartsUpdateQuoteUpdatePlugin extends AbstractPlugin implements QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - Adds shared cart list to multi cart collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if (count($quoteResponseTransfer->getSharedCustomerQuotes()->getQuotes())) {
            $multiCartClient = $this->getFactory()->getMultiCartClient();
            $customerQuoteCollectionTransfer = $multiCartClient->getQuoteCollection();
            foreach ($quoteResponseTransfer->getSharedCustomerQuotes()->getQuotes() as $quoteTransfer) {
                $customerQuoteCollectionTransfer->addQuote($quoteTransfer);
            }
            $multiCartClient->setQuoteCollection($customerQuoteCollectionTransfer);
        }

        return $quoteResponseTransfer;
    }
}

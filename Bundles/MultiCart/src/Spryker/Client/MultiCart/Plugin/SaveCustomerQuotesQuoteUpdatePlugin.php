<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class SaveCustomerQuotesQuoteUpdatePlugin extends AbstractPlugin implements QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - Extract Customer Quote Collection.
     * - Iterates over `QuoteResponseTransfer.customerQuotes.quotes`.
     * - Filters `QuoteTransfer` fields according to {@link \Spryker\Client\MultiCart\MultiCartConfig::getQuoteFieldsAllowedForCustomerQuoteCollectionInSession()}.
     * - If no fields are specified in config method, filtering is not applied.
     * - Saves `QuoteCollectionTransfer` to customer session.
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if ($quoteResponseTransfer->getCustomerQuotes()) {
            $this->getFactory()->createMultiCartStorage()->setQuoteCollection($quoteResponseTransfer->getCustomerQuotes());
        }

        return $quoteResponseTransfer;
    }
}

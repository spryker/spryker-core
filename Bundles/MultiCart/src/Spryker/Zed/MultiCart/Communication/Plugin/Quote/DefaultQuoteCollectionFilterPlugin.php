<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\MultiCart\MultiCartConfig getConfig()
 * @method \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCart\Communication\MultiCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface getRepository()
 */
class DefaultQuoteCollectionFilterPlugin extends AbstractPlugin implements QuoteCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `QuoteCriteriaFilterTransfer.isDefault` to be set.
     * - Filters out quotes that are not default.
     * - Returns a collection of quotes with only default quote.
     * - If default quote is not found, it will return an empty collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function filter(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer {
        if (!$quoteCriteriaFilterTransfer->getIsDefault()) {
            return $quoteCollectionTransfer;
        }

        $filteredQuoteCollectionTransfer = new QuoteCollectionTransfer();
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIsDefault()) {
                return $filteredQuoteCollectionTransfer->addQuote($quoteTransfer);
            }
        }

        return $filteredQuoteCollectionTransfer;
    }
}

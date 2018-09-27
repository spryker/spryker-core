<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;

/**
 * @method \Spryker\Client\Cart\CartClientInterface getClient()
 */
class CartQuoteCollectionReaderPlugin extends AbstractPlugin implements QuoteCollectionReaderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $quote = $this->getClient()->getQuote();
        $quoteCollection = new QuoteCollectionTransfer();
        $quoteCollection->addQuote($quote);

        return $quoteCollection;
    }
}

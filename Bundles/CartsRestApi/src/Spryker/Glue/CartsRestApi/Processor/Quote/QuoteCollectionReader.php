<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;

class QuoteCollectionReader implements QuoteCollectionReaderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface $cartClient
     */
    public function __construct(CartsRestApiToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = new QuoteCollectionTransfer();

        $quoteTransfer = $this->cartClient->getQuote();
        if ($quoteTransfer->getIdQuote() === null) {
            return $quoteCollectionTransfer;
        }

        return $quoteCollectionTransfer->addQuote($quoteTransfer);
    }
}

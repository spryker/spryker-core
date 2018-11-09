<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi\Reader;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;

class CartQuoteCollectionReader implements CartQuoteCollectionReaderInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface $cartClient
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;

class SingleQuoteCreator implements SingleQuoteCreatorInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface
     */
    protected $quoteCreator;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface $quoteCreator
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     */
    public function __construct(
        QuoteCreatorInterface $quoteCreator,
        QuoteReaderInterface $quoteReader
    ) {
        $this->quoteCreator = $quoteCreator;
        $this->quoteReader = $quoteReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createSingleQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($quoteTransfer->getCustomerReference())
            ->setIdStore($quoteTransfer->getStore()->getIdStore());

        $quoteCollectionTransfer = $this->quoteReader->getQuoteCollectionByQuoteCriteriaFilter($quoteCriteriaFilterTransfer);
        if ($quoteCollectionTransfer->getQuotes()->count()) {
            $quoteErrorTransfer = (new QuoteErrorTransfer())
                ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CUSTOMER_ALREADY_HAS_CART);

            return (new QuoteResponseTransfer())
                ->addError($quoteErrorTransfer)
                ->setIsSuccessful(false);
        }

        return $this->quoteCreator->createQuote($quoteCollectionTransfer->getQuotes()[0]);
    }
}

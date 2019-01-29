<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeInterface;

class MultipleQuoteReader implements MultipleQuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeInterface $multiCartsRestApiFacade
     */
    protected $multiCartsRestApiFacade;

    /**
     * @param \Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeInterface $multiCartsRestApiFacade
     */
    public function __construct(MultiCartsRestApiToMultiCartFacadeInterface $multiCartsRestApiFacade)
    {
        $this->multiCartsRestApiFacade = $multiCartsRestApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getCustomerQuoteCollection(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer {
        $quoteCollectionResponseTransfer = new QuoteCollectionResponseTransfer();
        $quoteCollectionTransfer = $this->getCustomerQuotes($restQuoteCollectionRequestTransfer);
        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $quoteCollectionResponseTransfer;
        }

        return $quoteCollectionResponseTransfer->setQuoteCollection($quoteCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function getCustomerQuotes(RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer): QuoteCollectionTransfer
    {
        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer->setCustomerReference($restQuoteCollectionRequestTransfer->getCustomerReference());
        $quoteCollectionTransfer = $this->multiCartsRestApiFacade->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);

        return $quoteCollectionTransfer;
    }
}

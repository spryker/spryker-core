<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeInterface;

class MultipleQuoteReader implements MultipleQuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeInterface $multiCartFacade
     */
    protected $multiCartFacade;

    /**
     * @param \Spryker\Zed\MultiCartsRestApi\Dependency\Facade\MultiCartsRestApiToMultiCartFacadeInterface $multiCartFacade
     */
    public function __construct(MultiCartsRestApiToMultiCartFacadeInterface $multiCartFacade)
    {
        $this->multiCartFacade = $multiCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getCustomerQuoteCollection(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer {
        $restQuoteCollectionRequestTransfer
            ->requireCustomerReference();

        $quoteCollectionResponseTransfer = new QuoteCollectionResponseTransfer();
        $quoteCollectionTransfer = $this->getCustomerQuotes($restQuoteCollectionRequestTransfer);
        if ($quoteCollectionTransfer->getQuotes()->count() === 0) {
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
        $quoteCollectionTransfer = $this->multiCartFacade->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);

        return $quoteCollectionTransfer;
    }
}

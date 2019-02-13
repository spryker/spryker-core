<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class SingleQuoteCreator implements SingleQuoteCreatorInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->persistentCartFacade = $persistentCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createSingleQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        $restQuoteCollectionRequestTransfer = (new RestQuoteCollectionRequestTransfer())
            ->setCustomerReference($restQuoteRequestTransfer->getCustomerReference());

        $quoteCollectionTransfer = $this->quoteReader->getCustomerQuoteCollection($restQuoteCollectionRequestTransfer);
        if ($quoteCollectionTransfer->getQuoteCollection()->getQuotes()->count()) {
            $quoteErrorTransfer = (new QuoteErrorTransfer())
                ->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_CUSTOMER_ALREADY_HAS_CART);

            return (new QuoteResponseTransfer())
                ->addError($quoteErrorTransfer)
                ->setIsSuccessful(false);
        }

        return $this->persistentCartFacade->createQuote($quoteCollectionTransfer->getQuoteCollection()->getQuotes()[0]);
    }
}

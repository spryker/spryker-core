<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class SingleQuoteCreator implements SingleQuoteCreatorInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(CartReaderInterface $cartReader, CartsRestApiClientInterface $cartsRestApiClient)
    {
        $this->cartReader = $cartReader;
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestRequestInterface $restRequest, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteCollectionTransfer = $this->cartReader->getCustomerQuotes($restRequest);
        if ($quoteCollectionTransfer->getQuotes()->count()) {
            $quoteErrorTransfer = (new QuoteErrorTransfer())
                ->setMessage(CartsRestApiConfig::EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART);

            return (new QuoteResponseTransfer())
                ->addError($quoteErrorTransfer)
                ->setQuoteTransfer($quoteTransfer)
                ->setIsSuccessful(false);
        }

        return $this->cartsRestApiClient->createQuote($quoteTransfer);
    }
}

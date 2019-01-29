<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartReader implements GuestCartReaderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     */
    public function __construct(
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartReaderInterface $cartReader
    ) {
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
        $this->cartReader = $cartReader;
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByIdentifier(string $uuidCart, RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->cartReader->readByIdentifier($uuidCart, $restRequest);
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteTransferByUuid(string $uuidCart, RestRequestInterface $restRequest): QuoteResponseTransfer
    {
        return $this->cartReader->getQuoteTransferByUuid($uuidCart, $restRequest);
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerQuoteByUuid(string $uuidCart, RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->cartReader->getCustomerQuoteByUuid($uuidCart, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getCustomerQuotes(RestRequestInterface $restRequest): QuoteCollectionTransfer
    {
        return $this->cartReader->getCustomerQuotes($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCurrentCustomerCarts(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getUser()) {
            return $this->guestCartRestResponseBuilder
                ->createAnonymousCustomerUniqueIdEmptyErrorRestResponse();
        }

        $quoteCollectionTransfer = $this->getCustomerQuotes($restRequest);
        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $this->guestCartRestResponseBuilder->createEmptyGuestCartRestResponse();
        }

        return $this->guestCartRestResponseBuilder
            ->createGuestCartRestResponse($quoteCollectionTransfer->getQuotes()->offsetGet(0));
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function getCustomerQuote(RestRequestInterface $restRequest): ?QuoteTransfer
    {
        $quoteResponseTransfers = $this->getCustomerQuotes($restRequest);
        $quotes = $quoteResponseTransfers->getQuotes();
        if (!$quotes->count()) {
            return null;
        }

        return $quotes->offsetGet(0);
    }
}

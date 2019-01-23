<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReader;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartReader extends CartReader implements GuestCartReaderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReader
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        QuoteCollectionReaderPluginInterface $quoteCollectionReader,
        CartsRestApiClientInterface $cartsRestApiClient,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
    ) {
        parent::__construct($cartRestResponseBuilder, $cartsResourceMapper, $quoteCollectionReader, $cartsRestApiClient);

        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
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

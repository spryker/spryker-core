<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartReader implements CartReaderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartsRestApiClientInterface $cartsRestApiClient
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByIdentifier(string $uuidCart, RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setUuid($uuidCart);

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (count($quoteResponseTransfer->getErrorCodes()) > 0) {
            return $this->cartRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $quoteResponseTransfer->getErrorCodes()
            );
        }

        $cartResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );

        return $this->cartRestResponseBuilder->createCartRestResponse($cartResource);
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerQuoteByUuid(string $uuidCart, RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setUuid($uuidCart);
        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false
            || $restRequest->getUser()->getNaturalIdentifier() !== $quoteResponseTransfer->getQuoteTransfer()->getCustomerReference()) {
            return $this->cartRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes($quoteResponseTransfer->getErrorCodes());
        }

        $cartResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );

        return $this->cartRestResponseBuilder->createCartRestResponse($cartResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCurrentCustomerCarts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteCollectionTransfer = $this->getCustomerQuotes($restRequest);

        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $this->cartRestResponseBuilder->createRestResponse();
        }

        return $this->getRestQuoteCollectionResponse($restRequest, $quoteCollectionTransfer);
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteTransferByUuid(string $uuidCart, RestRequestInterface $restRequest): QuoteResponseTransfer
    {
        $quoteCollectionTransfer = $this->getCustomerQuotes($restRequest);

        if ($quoteCollectionTransfer->getQuotes()->count() === 0) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false);
        }

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getUuid() === $uuidCart) {
                return (new QuoteResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setQuoteTransfer($quoteTransfer);
            }
        }

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false);
    }

    /**
     * @param bool $isSuccessful
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createRestQuoteResponseTransfer(
        bool $isSuccessful,
        ?QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setIsSuccessful($isSuccessful);

        if ($quoteTransfer) {
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getCustomerQuotes(RestRequestInterface $restRequest): QuoteCollectionTransfer
    {
        $restQuoteCollectionRequestTransfer = (new RestQuoteCollectionRequestTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $quoteCollectionTransfer = $this->cartsRestApiClient
            ->getCustomerQuoteCollection($restQuoteCollectionRequestTransfer);

        if (!$quoteCollectionTransfer->getQuoteCollection()) {
            return new QuoteCollectionTransfer();
        }

        return $quoteCollectionTransfer->getQuoteCollection();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getRestQuoteCollectionResponse(
        RestRequestInterface $restRequest,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->cartRestResponseBuilder->createRestResponse();
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $cartResource = $this->cartsResourceMapper->mapCartsResource($quoteTransfer, $restRequest);
            $restResponse->addResource($cartResource);
        }

        return $restResponse;
    }
}

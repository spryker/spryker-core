<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Carts;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToMultiCartClientInterface;
use Spryker\Glue\CartsRestApi\Exception\UserNotFound;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CartsReader implements CartsReaderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToMultiCartClientInterface $multiCartClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartsRestApiToMultiCartClientInterface $multiCartClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @param string $idQuote
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByIdentifier(string $idQuote, RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteResponseTransfer = $this->getQuoteTransferByUuid($idQuote, $restRequest);

        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            $response = $this->restResourceBuilder->createRestResponse();
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CartsRestApiConfig::RESPONSE_CODE_QUOTE_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(sprintf("Cart with id '%s' not found.", $idQuote));

            return $response->addError($restErrorTransfer);
        }

        return $this->getRestResponse($restRequest, $quoteResponseTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCurrentCustomerCarts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteCollectionTransfer = $this->getCustomerQuotes($restRequest);

        $response = $this->restResourceBuilder->createRestResponse(count($quoteCollectionTransfer->getQuotes()));
        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $response;
        }

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $cartResource = $this->cartsResourceMapper->mapCartsResource($quoteTransfer, $restRequest);
            $response->addResource($cartResource);
        }

        return $response;
    }

    /**
     * @param string $idQuote
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteTransferByUuid(string $idQuote, RestRequestInterface $restRequest): QuoteResponseTransfer
    {
        $quoteCollectionTransfer = $this->getCustomerQuotes($restRequest);

        if ($quoteCollectionTransfer->getQuotes()->count() === 0) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false);
        }

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getUuid() === $idQuote) {
                return (new QuoteResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setQuoteTransfer($quoteTransfer);
            }
        }

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\UserNotFound
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function getCustomerQuotes(RestRequestInterface $restRequest): QuoteCollectionTransfer
    {
        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        if ($restRequest->getUser() === null) {
            throw new UserNotFound(
                CartsRestApiConfig::EXCEPTION_MESSAGE_USER_MISSING,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $quoteCriteriaFilterTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
        $quoteCollectionTransfer = $this->multiCartClient->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getRestResponse(
        RestRequestInterface $restRequest,
        QuoteResponseTransfer $quoteResponseTransfer
    ): RestResponseInterface {
        $response = $this->restResourceBuilder->createRestResponse();
        $cartResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );
        $response->addResource($cartResource);

        return $response;
    }
}

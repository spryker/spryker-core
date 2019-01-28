<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
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
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReader;

    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReader
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        QuoteCollectionReaderPluginInterface $quoteCollectionReader,
        CartsRestApiClientInterface $cartsRestApiClient
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->quoteCollectionReader = $quoteCollectionReader;
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
        if (!$uuidCart) {
            return $this->cartRestResponseBuilder->createCartIdMissingErrorResponse();
        }

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid((new QuoteTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setUuid($uuidCart));

        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->cartRestResponseBuilder->createCartNotFoundErrorResponse();
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
        if (!$uuidCart) {
            return $this->cartRestResponseBuilder->createCartIdMissingErrorResponse();
        }

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid((new QuoteTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setUuid($uuidCart));

        if ($quoteResponseTransfer->getIsSuccessful() === false
            || $restRequest->getUser()->getNaturalIdentifier() !== $quoteResponseTransfer->getCustomer()->getCustomerReference()) {
            return $this->cartRestResponseBuilder->createCartNotFoundErrorResponse();
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getCustomerQuotes(RestRequestInterface $restRequest): QuoteCollectionTransfer
    {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $quoteCollectionTransfer = $this->quoteCollectionReader
            ->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);

        if (!$quoteCollectionTransfer) {
            return new QuoteCollectionTransfer();
        }

        return $quoteCollectionTransfer;
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

<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItems;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Exception\CartsRestApiException;
use Spryker\Glue\CartsRestApi\Processor\Carts\CartsReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CartItemsWriter implements CartItemsWriterInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Carts\CartsReaderInterface
     */
    protected $cartsReader;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Carts\CartsReaderInterface $cartsReader
     */
    public function __construct(
        CartsRestApiToCartClientInterface $cartClient,
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        RestResourceBuilderInterface $restResourceBuilder,
        CartsRestApiToZedRequestClientInterface $zedRequestClient,
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartsReaderInterface $cartsReader
    ) {
        $this->cartClient = $cartClient;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->zedRequestClient = $zedRequestClient;
        $this->quoteClient = $quoteClient;
        $this->cartsReader = $cartsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItem(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        try {
            $cartsResource = $this->getCartsResource($restRequest);
            $idQuote = $this->getCartIdentifier($cartsResource);
            $quoteResponseTransfer = $this->findQuote($restRequest, $idQuote);
        } catch (CartsRestApiException $exception) {
            return $this->createErrorResponse($exception);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $quoteTransfer = $this->cartClient->addItem(
            $this->prepareItemTransfer($restCartItemsAttributesTransfer)
        );

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->returnWithError($errors, $restResponse);
        }

        return $this->cartsReader->readByIdentifier($quoteTransfer->getUuid(), $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateQuantity(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $sku = '';
        $restResponse = $this->restResourceBuilder->createRestResponse();

        try {
            $cartsResource = $this->getCartsResource($restRequest);
            $idQuote = $this->getCartIdentifier($cartsResource);
            $itemIdentifier = $this->getCartItemIdentifier($restRequest);
            $quoteResponseTransfer = $this->findQuote($restRequest, $idQuote);
            $this->findQuoteItem($quoteResponseTransfer, $itemIdentifier);
        } catch (CartsRestApiException $exception) {
            return $this->createErrorResponse($exception);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $quoteTransfer = $this->cartClient->changeItemQuantity(
            $sku,
            $itemIdentifier,
            $restCartItemsAttributesTransfer->getQuantity()
        );

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->returnWithError($errors, $restResponse);
        }

        return $this->cartsReader->readByIdentifier($quoteTransfer->getUuid(), $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteItem(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $sku = '';
        $restResponse = $this->restResourceBuilder->createRestResponse();

        try {
            $cartsResource = $this->getCartsResource($restRequest);
            $idQuote = $this->getCartIdentifier($cartsResource);
            $itemIdentifier = $this->getCartItemIdentifier($restRequest);
            $quoteResponseTransfer = $this->findQuote($restRequest, $idQuote);
            $this->findQuoteItem($quoteResponseTransfer, $itemIdentifier);
        } catch (CartsRestApiException $exception) {
            return $this->createErrorResponse($exception);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $this->cartClient->removeItem($sku, $itemIdentifier);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function prepareItemTransfer(RestCartItemsAttributesTransfer $restCartItemsAttributesRequestTransfer): ItemTransfer
    {
        $itemTransfer = (new ItemTransfer())->fromArray(
            $restCartItemsAttributesRequestTransfer->toArray(),
            true
        );
        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $errors
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function returnWithError(array $errors, RestResponseInterface $restResponse): RestResponseInterface
    {
        foreach ($errors as $messageTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CartsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($messageTransfer->getValue());

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\CartsRestApiException
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getCartsResource(RestRequestInterface $restRequest): RestResourceInterface
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS);
        if ($cartsResource === null) {
            throw new CartsRestApiException(
                CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_ID_MISSING,
                Response::HTTP_BAD_REQUEST,
                CartsRestApiConfig::RESPONSE_CODE_QUOTE_ID_MISSING
            );
        }

        return $cartsResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartsResource
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\CartsRestApiException
     *
     * @return int
     */
    protected function getCartIdentifier(RestResourceInterface $cartsResource): int
    {
        $idQuote = $cartsResource->getId();
        if ($idQuote === null) {
            throw new CartsRestApiException(
                CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_ID_MISSING,
                Response::HTTP_BAD_REQUEST,
                CartsRestApiConfig::RESPONSE_CODE_QUOTE_ID_MISSING
            );
        }

        return $idQuote;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $idQuote
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\CartsRestApiException
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function findQuote(RestRequestInterface $restRequest, string $idQuote): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->cartsReader->getQuoteTransferByUuid($idQuote, $restRequest);
        if (!$quoteResponseTransfer->getIsSuccessful() || $quoteResponseTransfer->getQuoteTransfer() === null) {
            throw new CartsRestApiException(
                sprintf(CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_WITH_ID_NOT_FOUND, $idQuote),
                Response::HTTP_NOT_FOUND,
                CartsRestApiConfig::RESPONSE_CODE_QUOTE_NOT_FOUND
            );
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\CartsRestApiException
     *
     * @return string
     */
    protected function getCartItemIdentifier(RestRequestInterface $restRequest): string
    {
        $itemIdentifier = $restRequest->getResource()->getId();
        if ($itemIdentifier === null) {
            throw new CartsRestApiException(
                CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_ITEM_ID_MISSING,
                Response::HTTP_BAD_REQUEST,
                CartsRestApiConfig::RESPONSE_CODE_QUOTE_ITEM_ID_MISSING
            );
        }

        return $itemIdentifier;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param string $itemIdentifier
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\CartsRestApiException
     *
     * @return void
     */
    protected function findQuoteItem(QuoteResponseTransfer $quoteResponseTransfer, string $itemIdentifier): void
    {
        $sku = '';
        if ($this->cartClient->findQuoteItem($quoteResponseTransfer->getQuoteTransfer(), $sku, $itemIdentifier) === null) {
            throw new CartsRestApiException(
                sprintf(CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_ITEM_NOT_FOUND, $sku),
                Response::HTTP_NOT_FOUND,
                CartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND
            );
        }
    }

    /**
     * @param \Spryker\Glue\CartsRestApi\Exception\CartsRestApiException $exception
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(CartsRestApiException $exception): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode($exception->getErrorCode())
            ->setStatus($exception->getCode())
            ->setDetail($exception->getMessage());

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }
}

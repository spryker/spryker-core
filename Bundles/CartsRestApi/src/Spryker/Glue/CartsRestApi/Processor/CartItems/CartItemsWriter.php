<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItems;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Exception\QuoteItemNotFound;
use Spryker\Glue\CartsRestApi\Exception\QuoteNotFound;
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
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function add(
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $cartsResource = $this->getCartResource($restRequest);
        $idQuote = $this->getQuoteIdentifier($cartsResource);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $quoteResponseTransfer = $this->cartsReader->getQuoteTransferByUuid($idQuote, $restRequest);

        if (!$quoteResponseTransfer->getIsSuccessful() || $quoteResponseTransfer->getQuoteTransfer() === null) {
            return $this->createCartNotFoundError($idQuote, $restResponse);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $quoteTransfer = $this->cartClient->addItem(
            $this->prepareItemTransfer($restCartItemsAttributesTransfer)
        );

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->returnWithError($errors, $restResponse);
        }

        return $this->handleResponse($idQuote, $restCartItemsAttributesTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function patch(
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {

        $cartsResource = $this->getCartResource($restRequest);
        $idQuote = $this->getQuoteIdentifier($cartsResource);
        $sku = $this->getItemIdentifier($restRequest);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $quoteResponseTransfer = $this->cartsReader->getQuoteTransferByUuid($idQuote, $restRequest);

        if (!$quoteResponseTransfer->getIsSuccessful() || $quoteResponseTransfer->getQuoteTransfer() === null) {
            return $this->createCartNotFoundError($idQuote, $restResponse);
        }

        if (!$this->hasQuoteItem($quoteResponseTransfer->getQuoteTransfer(), $sku)) {
            return $this->createItemNotFoundError($sku, $restResponse);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        $quoteTransfer = $this->cartClient->changeItemQuantity(
            $restCartItemsAttributesTransfer->getSku(),
            $restCartItemsAttributesTransfer->getGroupKey(),
            $restCartItemsAttributesTransfer->getQuantity()
        );

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->returnWithError($errors, $restResponse);
        }

        return $this->handleResponse($idQuote, $restCartItemsAttributesTransfer, $quoteTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $cartsResource = $this->getCartResource($restRequest);
        $idQuote = $this->getQuoteIdentifier($cartsResource);
        $sku = $this->getItemIdentifier($restRequest);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $quoteResponseTransfer = $this->cartsReader->getQuoteTransferByUuid($idQuote, $restRequest);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCartNotFoundError($idQuote, $restResponse);
        }

        if (!$this->hasQuoteItem($quoteResponseTransfer->getQuoteTransfer(), $sku)) {
            return $this->createItemNotFoundError($sku, $restResponse);
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $this->cartClient->removeItem($sku);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     *
     * @return bool
     */
    protected function hasQuoteItem(QuoteTransfer $quoteTransfer, string $sku): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $sku) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findItemTransfer(
        RestCartItemsAttributesTransfer $restCartItemsAttributesRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): ?ItemTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $restCartItemsAttributesRequestTransfer->getSku()) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\QuoteNotFound
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getCartResource(RestRequestInterface $restRequest): RestResourceInterface
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS);
        if ($cartsResource === null) {
            throw new QuoteNotFound(
                CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_NOT_FOUND,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $cartsResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartsResource
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\QuoteNotFound
     *
     * @return string
     */
    protected function getQuoteIdentifier(RestResourceInterface $cartsResource): string
    {
        $idQuote = $cartsResource->getId();
        if ($idQuote === null) {
            throw new QuoteNotFound(
                CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_NOT_FOUND,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $idQuote;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @throws \Spryker\Glue\CartsRestApi\Exception\QuoteItemNotFound
     *
     * @return string
     */
    protected function getItemIdentifier(RestRequestInterface $restRequest)
    {
        $sku = $restRequest->getResource()->getId();
        if ($sku === null) {
            throw new QuoteItemNotFound(
                CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_NOT_FOUND,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $sku;
    }

    /**
     * @param string $idQuote
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartNotFoundError(string $idQuote, RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_QUOTE_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(sprintf("Cart with id '%s' not found.", $idQuote));

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createItemNotFoundError(string $sku, RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(sprintf("Cart item with sku '%s' not found.", $sku));

        return $restResponse->addError($restErrorTransfer);
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
     * @param string $idQuote
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function handleResponse(string $idQuote, RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer, QuoteTransfer $quoteTransfer)
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        /**
         * @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer
         */
        $itemTransfer = $this->findItemTransfer($restCartItemsAttributesTransfer, $quoteTransfer);
        if ($itemTransfer === null) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CartsRestApiConfig::RESPONSE_CODE_FAILED_ADDING_ITEM)
                ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setDetail(sprintf('Failed adding item "%s" to the cart "%s"', $restCartItemsAttributesTransfer->getSku(), $idQuote));

            return $restResponse->addError($restErrorMessageTransfer);
        }
        $restCartItemsAttributesResponseTransfer = $this->cartItemsResourceMapper->mapCartItemAttributes($itemTransfer);
        $cartItemsResource = $this->restResourceBuilder
            ->createRestResource(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $itemTransfer->getSku(),
                $restCartItemsAttributesResponseTransfer
            );

        return $restResponse->addResource($cartItemsResource);
    }
}

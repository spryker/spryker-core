<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItem;

use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CartItemUpdater implements CartItemUpdaterInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    protected $cartClient;

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
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     */
    public function __construct(
        CartsRestApiToCartClientInterface $cartClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CartsRestApiToZedRequestClientInterface $zedRequestClient,
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartReaderInterface $cartReader
    ) {
        $this->cartClient = $cartClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->zedRequestClient = $zedRequestClient;
        $this->quoteClient = $quoteClient;
        $this->cartReader = $cartReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateItemQuantity(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $sku = '';
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $idQuote = $this->findCartIdentifier($restRequest);
        $itemIdentifier = $restRequest->getResource()->getId();
        if ($this->isRequestValid($idQuote, $itemIdentifier)) {
            return $this->createMissingRequiredParameterError();
        }

        $quoteResponseTransfer = $this->cartReader->getQuoteTransferByUuid($idQuote, $restRequest);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteNotFoundError($idQuote);
        }

        if ($this->cartClient->findQuoteItem($quoteResponseTransfer->getQuoteTransfer(), $sku, $itemIdentifier) === null) {
            return $this->createQuoteItemNotFoundError($itemIdentifier);
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

        return $this->cartReader->readByIdentifier($quoteTransfer->getUuid(), $restRequest);
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
     * @return string|null
     */
    protected function findCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS);
        if ($cartsResource !== null) {
            return $cartsResource->getId();
        }

        return null;
    }

    /**
     * @param string $idQuote
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createQuoteNotFoundError(string $idQuote): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_QUOTE_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(sprintf(CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_WITH_ID_NOT_FOUND, $idQuote));

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createQuoteItemNotFoundError(string $sku): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(sprintf(CartsRestApiConfig::EXCEPTION_MESSAGE_QUOTE_ITEM_NOT_FOUND, $sku));

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createMissingRequiredParameterError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_MISSING_REQUIRED_PARAMETER)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param string|null $idQuote
     * @param string|null $itemIdentifier
     *
     * @return bool
     */
    protected function isRequestValid(?string $idQuote, ?string $itemIdentifier): bool
    {
        return ($idQuote === null || $itemIdentifier === null);
    }
}

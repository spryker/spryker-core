<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CartUpdater implements CartUpdaterInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        CartsResourceMapperInterface $cartsResourceMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(
        RestRequestInterface $restRequest,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $idCart = $restRequest->getResource()->getId();
        if ($idCart === null) {
            return $this->createCartIdMissingError($restResponse);
        }

        $currencyTransfer = $this->getCurrencyTransfer($restCartsAttributesTransfer);
        $customerTransfer = $this->getCustomerTransfer($restRequest);
        $storeTransfer = $this->getStoreTransfer($restCartsAttributesTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->fromArray($restCartsAttributesTransfer->modifiedToArray(), true)
            ->setUuid($idCart)
            ->setCurrency($currencyTransfer)
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        $quoteResponseTransfer = $this->cartsRestApiClient->updateQuoteByUuid($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCartNotFoundError($restResponse);
        }

        $restResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(RestCartsAttributesTransfer $restCartsAttributesTransfer): CurrencyTransfer
    {
        return (new CurrencyTransfer())
            ->setCode($restCartsAttributesTransfer->getCurrency());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(RestRequestInterface $restRequest): CustomerTransfer
    {
        return (new CustomerTransfer())->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(RestCartsAttributesTransfer $restCartsAttributesTransfer): StoreTransfer
    {
        return (new StoreTransfer())
            ->setName($restCartsAttributesTransfer->getStore());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartIdMissingError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING);

        return $response->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND);

        return $response->addError($restErrorTransfer);
    }
}

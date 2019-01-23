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
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

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
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartRestResponseBuilderInterface $cartRestResponseBuilder
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
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
        $idCart = $restRequest->getResource()->getId();
        if ($idCart === null) {
            return $this->cartRestResponseBuilder->createCartIdMissingErrorResponse();
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

        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setQuoteUuid($idCart);

        $quoteResponseTransfer = $this->cartsRestApiClient->updateQuote($restQuoteRequestTransfer);

        if ($quoteResponseTransfer->getErrors()->count()) {
            return $this->cartRestResponseBuilder->createQuoteErrorResponse($quoteResponseTransfer);
        }

        $restResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );

        return $this->cartRestResponseBuilder->createCartRestResponse($restResource);
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
        return (new StoreTransfer())->setName($restCartsAttributesTransfer->getStore());
    }
}

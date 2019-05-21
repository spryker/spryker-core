<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected $quoteCustomerExpanderPlugins;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[] $quoteCustomerExpanderPlugins
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartsRestApiClientInterface $cartsRestApiClient,
        array $quoteCustomerExpanderPlugins
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->quoteCustomerExpanderPlugins = $quoteCustomerExpanderPlugins;
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
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setUuid($uuidCart);

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);
        if ($quoteResponseTransfer->getErrors()->count() > 0) {
            return $this->cartRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
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
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setCustomer(new CustomerTransfer())
            ->setUuid($uuidCart);

        $quoteTransfer->setCustomer(
            $this->executeCustomerExpanderPlugin($quoteTransfer->getCustomer(), $restRequest)
        );

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
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
        $quoteCollectionTransfer = $this->cartsRestApiClient->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())
                ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
        );

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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executeCustomerExpanderPlugin(CustomerTransfer $customerTransfer, RestRequestInterface $restRequest): CustomerTransfer
    {
        foreach ($this->quoteCustomerExpanderPlugins as $quoteCustomerExpanderPlugin) {
            $customerTransfer = $quoteCustomerExpanderPlugin->expand($customerTransfer, $restRequest);
        }

        return $customerTransfer;
    }
}

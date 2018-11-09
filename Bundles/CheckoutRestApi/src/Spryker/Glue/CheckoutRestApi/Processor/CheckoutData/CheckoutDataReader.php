<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutDataReader implements CheckoutDataReaderInterface
{
    /**
     * @var \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface
     */
    protected $checkoutRestApiClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReader;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface
     */
    protected $quoteProcessor;

    /**
     * @param \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface $checkoutDataMapper
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReader
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface $quoteProcessor
     */
    public function __construct(
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutDataMapperInterface $checkoutDataMapper,
        QuoteCollectionReaderPluginInterface $quoteCollectionReader,
        QuoteProcessorInterface $quoteProcessor
    ) {
        $this->checkoutRestApiClient = $checkoutRestApiClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->checkoutDataMapper = $checkoutDataMapper;
        $this->quoteCollectionReader = $quoteCollectionReader;
        $this->quoteProcessor = $quoteProcessor;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCheckoutData(RestRequestInterface $restRequest, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestResponseInterface
    {
        $currentCustomerQuote = $this->findQuoteTransfer($restCheckoutRequestAttributesTransfer);
        if ($currentCustomerQuote === null) {
            return $this->createCartNotFoundErrorResponse();
        }

        $quoteTransfer = $this->checkoutDataMapper->mapRestCheckoutRequestAttributesTransferToQuoteTransfer(
            $currentCustomerQuote,
            $restCheckoutRequestAttributesTransfer,
            false
        );

        $checkoutDataTransfer = $this->checkoutRestApiClient->getCheckoutData($quoteTransfer);

        $restCheckoutResponseAttributesTransfer = $this->checkoutDataMapper
            ->mapCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer($checkoutDataTransfer, $restCheckoutRequestAttributesTransfer);

        return $this->createRestResponse($restCheckoutResponseAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer): RestResponseInterface
    {
        $checkoutDataResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
            null,
            $restCheckoutResponseAttributesTransfer
        );

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addResource($checkoutDataResource);
        $restResponse->setStatus(Response::HTTP_OK);

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CART_NOT_FOUND);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError($restErrorMessageTransfer);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteTransfer(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        if ($restCheckoutRequestAttributesTransfer->getCart() === null
            || ($restCheckoutRequestAttributesTransfer->getCart() !== null && $restCheckoutRequestAttributesTransfer->getCart()->getId() === null)) {
            return $this->findCurrentCustomerQuote();
        }

        return $this->quoteProcessor->findCustomerQuote($restCheckoutRequestAttributesTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findCurrentCustomerQuote(): ?QuoteTransfer
    {
        $quoteCollectionTransfer = $this->quoteCollectionReader->getQuoteCollectionByCriteria(new QuoteCriteriaFilterTransfer());

        if (!$quoteCollectionTransfer->getQuotes()->offsetExists(0)) {
            return null;
        }

        return $quoteCollectionTransfer->getQuotes()->offsetGet(0);
    }
}

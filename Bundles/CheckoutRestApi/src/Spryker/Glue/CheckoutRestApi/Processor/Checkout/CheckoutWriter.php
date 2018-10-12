<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutWriter implements CheckoutWriterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReader;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReader
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface $checkoutDataMapper
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface $customerClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutRestApiToCheckoutClientInterface $checkoutClient,
        CheckoutRestApiToQuoteClientInterface $quoteClient,
        CheckoutRestApiToCartClientInterface $cartClient,
        QuoteCollectionReaderPluginInterface $quoteCollectionReader,
        CheckoutDataMapperInterface $checkoutDataMapper,
        CheckoutRestApiToCustomerClientInterface $customerClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->checkoutClient = $checkoutClient;
        $this->quoteClient = $quoteClient;
        $this->cartClient = $cartClient;
        $this->quoteCollectionReader = $quoteCollectionReader;
        $this->checkoutDataMapper = $checkoutDataMapper;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(RestRequestInterface $restRequest, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestResponseInterface
    {
        $quoteTransfer = $this->getCustomerQuote($restCheckoutRequestAttributesTransfer);
        if ($quoteTransfer === null) {
            return $this->createQuoteNotFoundError();
        }

        $quoteResponseTransfer = $this->cartClient->validateQuote();

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createDataInvalidError();
        }

        $quoteTransfer = $this->checkoutDataMapper
            ->mapRestCheckoutRequestAttributesTransferToQuoteTransfer(
                $quoteTransfer,
                $restCheckoutRequestAttributesTransfer
            );

        $customerResponseTransfer = $this->customerClient->findCustomerByReference((new CustomerTransfer())->setCustomerReference($restRequest->getUser()->getNaturalIdentifier()));
        $quoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());

        $this->quoteClient->setQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->returnWithError($checkoutResponseTransfer->getErrors());
        }

        $restResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            $checkoutResponseTransfer->getSaveOrder()->getOrderReference(),
            (new RestCheckoutResponseAttributesTransfer())
                ->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference())
        );
        $restResponse = $this->restResourceBuilder->createRestResponse();
        return $restResponse->addResource($restResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createQuoteNotFoundError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_QUOTE_NOT_FOUND)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_QUOTE_NOT_FOUND)
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createDataInvalidError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_DATA_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_DATA_INVALID)
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createOrderNotPlacedError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_ORDER_NOT_PLACED)
        );

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function getCustomerQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        $quoteTransfer = null;
        $quoteIdentifier = $restCheckoutRequestAttributesTransfer->getQuote()->getQuoteIdentifier();
        $quoteCollectionTransfer = $this->quoteCollectionReader->getQuoteCollectionByCriteria(new QuoteCriteriaFilterTransfer());
        foreach ($quoteCollectionTransfer->getQuotes() as $customerQuote) {
            if ($customerQuote->getUuid() == $quoteIdentifier) {
                $quoteTransfer = $customerQuote;
                break;
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer[]|\ArrayObject $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function returnWithError(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $checkoutErrorTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($checkoutErrorTransfer->getMessage());

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }
}

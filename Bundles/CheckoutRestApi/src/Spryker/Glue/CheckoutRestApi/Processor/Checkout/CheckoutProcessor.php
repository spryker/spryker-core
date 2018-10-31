<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutProcessor implements CheckoutProcessorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface
     */
    protected $quoteProcessor;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface
     */
    protected $quoteMerger;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface $quoteProcessor
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface $quoteMerger
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteProcessorInterface $quoteProcessor,
        QuoteMergerInterface $quoteMerger,
        CheckoutRestApiToCheckoutClientInterface $checkoutClient,
        CheckoutRestApiToZedRequestClientInterface $zedRequestClient,
        CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteProcessor = $quoteProcessor;
        $this->quoteMerger = $quoteMerger;
        $this->checkoutClient = $checkoutClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(RestRequestInterface $restRequest, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestResponseInterface
    {
        $quoteTransfer = $this->quoteProcessor->findCustomerQuote($restCheckoutRequestAttributesTransfer);
        if ($quoteTransfer === null) {
            return $this->createCartNotFoundErrorResponse();
        }

        $quoteResponseTransfer = $this->quoteProcessor->validateQuote();
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createErrorMessagesResponse($this->zedRequestClient->getLastResponseErrorMessages());
        }

        $quoteTransfer = $this->quoteMerger->updateQuoteWithDataFromRequest(
            $quoteTransfer,
            $restCheckoutRequestAttributesTransfer,
            $restRequest
        );

        $errors = $this->validateRequiredData($quoteTransfer);
        if ($errors !== null) {
            return $this->createErrorMessagesResponseFromRestErrors($errors);
        }

        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderFailedErrorResponse($checkoutResponseTransfer->getErrors(), $restRequest->getMetadata()->getLocale());
        }

        $this->quoteProcessor->clearQuote();

        return $this->createOrderPlacedResponse($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartNotFoundErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_CART_NOT_FOUND)
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCheckoutDataInvalidErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CHECKOUT_DATA_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_CHECKOUT_DATA_INVALID)
        );

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer[]|\ArrayObject $errors
     * @param string $currentLocale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createPlaceOrderFailedErrorResponse(ArrayObject $errors, string $currentLocale): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $checkoutErrorTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($this->translateCheckoutErrorMessage($checkoutErrorTransfer, $currentLocale));

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     * @param string $currentLocale
     *
     * @return string
     */
    protected function translateCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer, string $currentLocale): string
    {
        $checkoutErrorMessage = $checkoutErrorTransfer->getMessage();

        return $this->glossaryStorageClient->translate(
            $checkoutErrorMessage,
            $currentLocale,
            $checkoutErrorTransfer->getParameters()
        ) ?: $checkoutErrorMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorMessagesResponse(array $messageTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($messageTransfers as $messageTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($messageTransfer->getValue());

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param string $orderReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createOrderPlacedResponse(string $orderReference): RestResponseInterface
    {
        $restResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            null,
            (new RestCheckoutResponseAttributesTransfer())->setOrderReference($orderReference)
        );
        $restResponse = $this->restResourceBuilder->createRestResponse();

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array|null
     */
    protected function validateRequiredData(QuoteTransfer $quoteTransfer): ?array
    {
        $errors = null;
        if ($quoteTransfer->getUuid() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_CART_NOT_FOUND);
        }
        if ($quoteTransfer->getCustomer() !== null && $quoteTransfer->getCustomer()->getEmail() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CUSTOMER_EMAIL_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_CUSTOMER_EMAIL_MISSING);
        }

        if ($quoteTransfer->getPayments()->count() === 0 || $quoteTransfer->getPayment() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_PAYMENT_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_PAYMENT_MISSING);
        }
        if ($quoteTransfer->getPayment() !== null && !$this->isPaymentsDataValid($quoteTransfer)) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_PAYMENT_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_PAYMENT_INVALID);
        }

        if ($quoteTransfer->getShipment() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_SHIPPING_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_SHIPPING_MISSING);
        }
        if ($quoteTransfer->getShipment() !== null && $quoteTransfer->getShipment()->getShipmentSelection() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_SHIPPING_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_SHIPPING_INVALID);
        }
        if ($quoteTransfer->getBillingAddress() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_BILLING_ADDRESS_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_BILLING_ADDRESS_MISSING);
        }
        if ($quoteTransfer->getBillingAddress() !== null && $this->isAddressValid($quoteTransfer->getBillingAddress())) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_BILLING_ADDRESS_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_BILLING_ADDRESS_INVALID);
        }
        if ($quoteTransfer->getShippingAddress() === null) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_SHIPPING_ADDRESS_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_SHIPPING_ADDRESS_MISSING);
        }
        if ($quoteTransfer->getShippingAddress() !== null && $this->isAddressValid($quoteTransfer->getShippingAddress())) {
            $errors[] = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_SHIPPING_ADDRESS_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_SHIPPING_ADDRESS_INVALID);
        }

        return $errors;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isPaymentsDataValid(QuoteTransfer $quoteTransfer): bool
    {
        $isPaymentInvalid = !$quoteTransfer->getPayment()->getPaymentSelection()
            || !$quoteTransfer->getPayment()->getPaymentMethod()
            || !$quoteTransfer->getPayment()->getAmount()
            || !$quoteTransfer->getPayment()->getPaymentProvider();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if (!$paymentTransfer->getPaymentSelection()
                || !$paymentTransfer->getPaymentMethod()
                || !$paymentTransfer->getAmount()
                || !$paymentTransfer->getPaymentProvider()) {
                $isPaymentInvalid = false;
            }
        }

        return !$isPaymentInvalid;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isAddressValid(AddressTransfer $addressTransfer): bool
    {
        return !(
            !$addressTransfer->getAddress1()
            || !$addressTransfer->getAddress2()
            || !$addressTransfer->getCity()
            || !$addressTransfer->getZipCode()
            || !$addressTransfer->getLastName()
            || !$addressTransfer->getFirstName()
            || !$addressTransfer->getIso2Code()
            || !$addressTransfer->getSalutation()
        ) && !$addressTransfer->getUuid();
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer[] $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorMessagesResponseFromRestErrors(array $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $error) {
            $restResponse->addError($error);
        }

        return $restResponse;
    }
}

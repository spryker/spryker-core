<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Payment;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestPreOrderPaymentCancellationRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponse;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientInterface;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class Payment implements PaymentInterface
{
    /**
     * @var \Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientInterface
     */
    protected PaymentsRestApiToPaymentClientInterface $paymentClient;

    /**
     * @param \Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientInterface $paymentClient
     */
    public function __construct(PaymentsRestApiToPaymentClientInterface $paymentClient)
    {
        $this->paymentClient = $paymentClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer $restPreOrderPaymentRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function initializePreOrderPayment(
        RestRequestInterface $restRequest,
        RestPreOrderPaymentRequestAttributesTransfer $restPreOrderPaymentRequestAttributesTransfer
    ): RestResponseInterface {
        $preOrderPaymentRequestTransfer = (new PreOrderPaymentRequestTransfer())->fromArray($restPreOrderPaymentRequestAttributesTransfer->toArray(), true);
        $preOrderPaymentRequestTransfer->setPayment(
            (new PaymentTransfer())
                ->setPaymentProvider($restPreOrderPaymentRequestAttributesTransfer->getPaymentOrFail()->getPaymentProviderNameOrFail())
                ->setPaymentMethod($restPreOrderPaymentRequestAttributesTransfer->getPaymentOrFail()->getPaymentMethodNameOrFail())
                ->setAmount($restPreOrderPaymentRequestAttributesTransfer->getPaymentOrFail()->getAmountOrFail()),
        );
        $preOrderPaymentRequestTransfer->getQuoteOrFail()->setPayment($preOrderPaymentRequestTransfer->getPaymentOrFail());

        $preOrderPaymentResponseTransfer = $this->paymentClient->initializePreOrderPayment($preOrderPaymentRequestTransfer);

        $restResource = new RestResource(PaymentsRestApiConfig::RESOURCE_TYPE_PAYMENTS, null, $preOrderPaymentResponseTransfer);

        $restResponse = new RestResponse();
        $restResponse->addResource($restResource);

        if ($preOrderPaymentResponseTransfer->getIsSuccessful() === false) {
            $restErrorMessageTransfer = new RestErrorMessageTransfer();
            $restErrorMessageTransfer
                ->setDetail($preOrderPaymentResponseTransfer->getError())
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestPreOrderPaymentCancellationRequestAttributesTransfer $restPreOrderPaymentCancellationRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function cancelPreOrderPayment(
        RestRequestInterface $restRequest,
        RestPreOrderPaymentCancellationRequestAttributesTransfer $restPreOrderPaymentCancellationRequestAttributesTransfer
    ): RestResponseInterface {
        $preOrderPaymentRequestTransfer = (new PreOrderPaymentRequestTransfer())->fromArray($restPreOrderPaymentCancellationRequestAttributesTransfer->toArray(), true);
        $preOrderPaymentRequestTransfer->setPayment(
            (new PaymentTransfer())
                ->setPaymentProvider($restPreOrderPaymentCancellationRequestAttributesTransfer->getPaymentOrFail()->getPaymentProviderNameOrFail())
                ->setPaymentMethod($restPreOrderPaymentCancellationRequestAttributesTransfer->getPaymentOrFail()->getPaymentMethodNameOrFail()),
        );

        $preOrderPaymentResponseTransfer = $this->paymentClient->cancelPreOrderPayment($preOrderPaymentRequestTransfer);

        $restResource = new RestResource(PaymentsRestApiConfig::RESOURCE_TYPE_PAYMENT_CANCELLATIONS, null, $preOrderPaymentResponseTransfer);

        $restResponse = new RestResponse();
        $restResponse->addResource($restResource);

        if ($preOrderPaymentResponseTransfer->getIsSuccessful() === false) {
            $restErrorMessageTransfer = new RestErrorMessageTransfer();
            $restErrorMessageTransfer
                ->setDetail($preOrderPaymentResponseTransfer->getError())
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }
}

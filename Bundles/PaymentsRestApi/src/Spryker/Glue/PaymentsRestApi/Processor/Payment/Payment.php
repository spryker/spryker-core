<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Payment;

use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\RestPaymentCustomersRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPreOrderPaymentCancellationRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentAppClientInterface;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientInterface;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentCustomerRestResponseBuilderInterface;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface;

class Payment implements PaymentInterface
{
    /**
     * @var \Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientInterface
     */
    protected PaymentsRestApiToPaymentClientInterface $paymentClient;

    /**
     * @var \Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentAppClientInterface
     */
    protected PaymentsRestApiToPaymentAppClientInterface $paymentAppClient;

    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface
     */
    protected PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder;

    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentCustomerRestResponseBuilderInterface
     */
    protected PaymentCustomerRestResponseBuilderInterface $paymentCustomerRestResponseBuilder;

    /**
     * @param \Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientInterface $paymentClient
     * @param \Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentAppClientInterface $paymentAppClient
     * @param \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder
     * @param \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentCustomerRestResponseBuilderInterface $paymentCustomerRestResponseBuilder
     */
    public function __construct(
        PaymentsRestApiToPaymentClientInterface $paymentClient,
        PaymentsRestApiToPaymentAppClientInterface $paymentAppClient,
        PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder,
        PaymentCustomerRestResponseBuilderInterface $paymentCustomerRestResponseBuilder
    ) {
        $this->paymentClient = $paymentClient;
        $this->paymentAppClient = $paymentAppClient;
        $this->paymentMethodRestResponseBuilder = $paymentMethodRestResponseBuilder;
        $this->paymentCustomerRestResponseBuilder = $paymentCustomerRestResponseBuilder;
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
                ->setPaymentProviderName($restPreOrderPaymentRequestAttributesTransfer->getPaymentOrFail()->getPaymentProviderNameOrFail())
                ->setPaymentMethodName($restPreOrderPaymentRequestAttributesTransfer->getPaymentOrFail()->getPaymentMethodNameOrFail())
                ->setAmount($restPreOrderPaymentRequestAttributesTransfer->getPaymentOrFail()->getAmountOrFail()),
        );
        $preOrderPaymentRequestTransfer->getQuoteOrFail()->setPayment($preOrderPaymentRequestTransfer->getPaymentOrFail());

        $preOrderPaymentResponseTransfer = $this->paymentClient->initializePreOrderPayment($preOrderPaymentRequestTransfer);

        return $this->paymentMethodRestResponseBuilder->createPaymentsRestResponse(
            $preOrderPaymentResponseTransfer,
        );
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
        $preOrderPaymentRequestTransfer = (new PreOrderPaymentRequestTransfer())->fromArray(
            $restPreOrderPaymentCancellationRequestAttributesTransfer->toArray(),
            true,
        );
        $preOrderPaymentRequestTransfer->setPayment(
            (new PaymentTransfer())
                ->setPaymentProviderName($restPreOrderPaymentCancellationRequestAttributesTransfer->getPaymentOrFail()->getPaymentProviderNameOrFail())
                ->setPaymentMethodName($restPreOrderPaymentCancellationRequestAttributesTransfer->getPaymentOrFail()->getPaymentMethodNameOrFail()),
        );

        $preOrderPaymentResponseTransfer = $this->paymentClient->cancelPreOrderPayment($preOrderPaymentRequestTransfer);

        return $this->paymentMethodRestResponseBuilder->createPaymentCancellationsRestResponse(
            $preOrderPaymentResponseTransfer,
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestPaymentCustomersRequestAttributesTransfer $restPaymentCustomersRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomer(
        RestRequestInterface $restRequest,
        RestPaymentCustomersRequestAttributesTransfer $restPaymentCustomersRequestAttributesTransfer
    ): RestResponseInterface {
        $paymentCustomerRequestTransfer = (new PaymentCustomerRequestTransfer())->fromArray($restPaymentCustomersRequestAttributesTransfer->toArray(), true);

        $paymentCustomerResponseTransfer = $this->paymentAppClient->getCustomer($paymentCustomerRequestTransfer);

        return $this->paymentCustomerRestResponseBuilder->createPaymentCustomersRestResponse(
            $paymentCustomerResponseTransfer,
        );
    }
}

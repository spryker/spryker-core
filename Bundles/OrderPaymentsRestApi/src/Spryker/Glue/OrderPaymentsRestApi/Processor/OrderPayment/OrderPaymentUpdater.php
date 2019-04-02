<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentUpdater implements OrderPaymentUpdaterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface
     */
    protected $orderPaymentsRestApiClient;

    /**
     * @var \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapperInterface
     */
    protected $orderPaymentMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface $orderPaymentsRestApiClient
     * @param \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapperInterface $orderPaymentMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        OrderPaymentsRestApiClientInterface $orderPaymentsRestApiClient,
        OrderPaymentMapperInterface $orderPaymentMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->orderPaymentsRestApiClient = $orderPaymentsRestApiClient;
        $this->orderPaymentMapper = $orderPaymentMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateOrderPayment(
        RestRequestInterface $restRequest,
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $updateOrderPaymentRequestTransfer = $this->orderPaymentMapper
            ->mapRestOrderPaymentsAttributesTransferToUpdateOrderPaymentRequestTransfer($restOrderPaymentsAttributesTransfer);
        $updateOrderPaymentResponseTransfer = $this->orderPaymentsRestApiClient->updateOrderPayment($updateOrderPaymentRequestTransfer);
        if (!$updateOrderPaymentResponseTransfer->getIsSuccessful()) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(OrderPaymentsRestApiConfig::RESPONSE_CODE_ORDER_PAYMENT_IS_NOT_UPDATED)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(OrderPaymentsRestApiConfig::RESPONSE_MESSAGE_ORDER_PAYMENT_IS_NOT_UPDATED);

            return $restResponse->addError($restErrorTransfer);
        }

        $orderPaymentsResource = $this->restResourceBuilder->createRestResource(
            OrderPaymentsRestApiConfig::RESOURCE_ORDER_PAYMENTS,
            $restOrderPaymentsAttributesTransfer->getPaymentIdentifier(),
            $this->orderPaymentMapper
                ->mapUpdateOrderPaymentResponseTransferToRestOrderPaymentsAttributesTransfer($updateOrderPaymentResponseTransfer)
        );

        return $restResponse->addResource($orderPaymentsResource);
    }
}

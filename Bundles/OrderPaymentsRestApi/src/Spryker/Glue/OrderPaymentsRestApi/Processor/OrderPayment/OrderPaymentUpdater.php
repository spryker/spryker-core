<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment;

use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiConfig;
use Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder\OrderPaymentRestResponseBuilderInterface;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentUpdater implements OrderPaymentUpdaterInterface
{
    /**
     * @var \Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder\OrderPaymentRestResponseBuilderInterface
     */
    protected $orderPaymentRestResponseBuilder;

    /**
     * @var \Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface
     */
    protected $orderPaymentsRestApiClient;

    /**
     * @var \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapperInterface
     */
    protected $orderPaymentMapper;

    /**
     * @param \Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder\OrderPaymentRestResponseBuilderInterface $orderPaymentRestResponseBuilder
     * @param \Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface $orderPaymentsRestApiClient
     * @param \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapperInterface $orderPaymentMapper
     */
    public function __construct(
        OrderPaymentRestResponseBuilderInterface $orderPaymentRestResponseBuilder,
        OrderPaymentsRestApiClientInterface $orderPaymentsRestApiClient,
        OrderPaymentMapperInterface $orderPaymentMapper
    ) {
        $this->orderPaymentRestResponseBuilder = $orderPaymentRestResponseBuilder;
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
        $updateOrderPaymentRequestTransfer = $this->orderPaymentMapper
            ->mapRestOrderPaymentsAttributesTransferToUpdateOrderPaymentRequestTransfer(
                $restOrderPaymentsAttributesTransfer,
                new UpdateOrderPaymentRequestTransfer()
            );

        $updateOrderPaymentResponseTransfer = $this->orderPaymentsRestApiClient
            ->updateOrderPayment($updateOrderPaymentRequestTransfer);
        if (!$updateOrderPaymentResponseTransfer->getIsSuccessful()) {
            return $this->orderPaymentRestResponseBuilder->buildErrorRestResponse(
                OrderPaymentsRestApiConfig::RESPONSE_CODE_ORDER_PAYMENT_IS_NOT_UPDATED,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                OrderPaymentsRestApiConfig::RESPONSE_MESSAGE_ORDER_PAYMENT_IS_NOT_UPDATED
            );
        }

        $restOrderPaymentsAttributesTransfer = $this->orderPaymentMapper
            ->mapUpdateOrderPaymentResponseTransferToRestOrderPaymentsAttributesTransfer(
                $updateOrderPaymentResponseTransfer,
                $restOrderPaymentsAttributesTransfer
            );

        return $this->orderPaymentRestResponseBuilder
            ->createOrderPaymentRestResponse($restOrderPaymentsAttributesTransfer);
    }
}

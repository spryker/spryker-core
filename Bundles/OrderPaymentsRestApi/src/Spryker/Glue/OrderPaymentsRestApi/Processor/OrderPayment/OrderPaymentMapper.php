<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment;

use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiConfig;

class OrderPaymentMapper implements OrderPaymentMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer
     */
    public function mapRestOrderPaymentsAttributesTransferToUpdateOrderPaymentRequestTransfer(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): UpdateOrderPaymentRequestTransfer {
        return (new UpdateOrderPaymentRequestTransfer())
            ->fromArray($restOrderPaymentsAttributesTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer
     */
    public function mapUpdateOrderPaymentResponseTransferToRestOrderPaymentsAttributesTransfer(
        UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer
    ): RestOrderPaymentsAttributesTransfer {
        return (new RestOrderPaymentsAttributesTransfer())
            ->fromArray($updateOrderPaymentResponseTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapOrderPaymentResource(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): RestResourceInterface {
        $orderPaymentResource = $this->restResourceBuilder->createRestResource(
            OrderPaymentsRestApiConfig::RESOURCE_ORDER_PAYMENTS,
            $restOrderPaymentsAttributesTransfer->getPaymentIdentifier(),
            $restOrderPaymentsAttributesTransfer
        );

        return $orderPaymentResource;
    }
}

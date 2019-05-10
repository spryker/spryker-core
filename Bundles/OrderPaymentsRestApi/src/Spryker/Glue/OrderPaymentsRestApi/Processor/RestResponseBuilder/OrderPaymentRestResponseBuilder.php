<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiConfig;

class OrderPaymentRestResponseBuilder implements OrderPaymentRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createOrderPaymentRestResponse(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): RestResponseInterface {
        $orderPaymentRestResource = $this->restResourceBuilder->createRestResource(
            OrderPaymentsRestApiConfig::RESOURCE_ORDER_PAYMENTS,
            $restOrderPaymentsAttributesTransfer->getPaymentIdentifier(),
            $restOrderPaymentsAttributesTransfer
        );

        return $this->restResourceBuilder->createRestResponse()->addResource($orderPaymentRestResource);
    }

    /**
     * @param string $errorCode
     * @param int $status
     * @param string $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorRestResponse(
        string $errorCode,
        int $status,
        string $errorMessage
    ): RestResponseInterface {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode($errorCode)
            ->setStatus($status)
            ->setDetail($errorMessage);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }
}

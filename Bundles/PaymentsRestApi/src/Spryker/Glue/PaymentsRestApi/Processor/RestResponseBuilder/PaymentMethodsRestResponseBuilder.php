<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;

class PaymentMethodsRestResponseBuilder implements PaymentMethodsRestResponseBuilderInterface
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
     * @param int $idPaymentMethod
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer $restPaymentMethodsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createRestPaymentMethodsResource(
        int $idPaymentMethod,
        RestPaymentMethodsAttributesTransfer $restPaymentMethodsAttributesTransfer
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS,
            (string)$idPaymentMethod,
            $restPaymentMethodsAttributesTransfer
        );
    }
}

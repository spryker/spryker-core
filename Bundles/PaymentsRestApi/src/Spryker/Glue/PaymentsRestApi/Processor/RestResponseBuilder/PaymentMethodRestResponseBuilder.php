<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;
use Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface;

class PaymentMethodRestResponseBuilder implements PaymentMethodRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface
     */
    protected $paymentMethodMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        PaymentMethodMapperInterface $paymentMethodMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->paymentMethodMapper = $paymentMethodMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $checkoutDataRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createRestPaymentMethodsResources(RestResourceInterface $checkoutDataRestResource): array
    {
        $resources = [];

        $restCheckoutDataTransfer = $checkoutDataRestResource->getPayload();
        if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
            return $resources;
        }

        $restPaymentMethodsAttributesTransfers = $this->paymentMethodMapper
            ->mapRestCheckoutDataTransferToRestPaymentMethodsAttributesTransfers($restCheckoutDataTransfer);

        foreach ($restPaymentMethodsAttributesTransfers as $idPaymentMethod => $restPaymentMethodsAttributesTransfer) {
            $resources[] = $this->restResourceBuilder->createRestResource(
                PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS,
                (string)$idPaymentMethod,
                $restPaymentMethodsAttributesTransfer
            );
        }

        return $resources;
    }
}

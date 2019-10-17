<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
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
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createRestPaymentMethodsResources(RestCheckoutDataTransfer $restCheckoutDataTransfer): array
    {
        $restResources = [];

        $restPaymentMethodsAttributesTransfers = $this->paymentMethodMapper
            ->mapRestCheckoutDataTransferToRestPaymentMethodsAttributesTransfers($restCheckoutDataTransfer);

        foreach ($restPaymentMethodsAttributesTransfers as $idPaymentMethod => $restPaymentMethodsAttributesTransfer) {
            $restResources[] = $this->restResourceBuilder->createRestResource(
                PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS,
                (string)$idPaymentMethod,
                $restPaymentMethodsAttributesTransfer
            );
        }

        return $restResources;
    }
}

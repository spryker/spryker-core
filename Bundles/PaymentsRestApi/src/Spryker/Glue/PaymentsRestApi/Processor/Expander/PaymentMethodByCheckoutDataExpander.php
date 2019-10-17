<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface;

class PaymentMethodByCheckoutDataExpander implements PaymentMethodByCheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface
     */
    protected $paymentMethodRestResponseBuilder;

    /**
     * @param \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder
     */
    public function __construct(
        PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder
    ) {
        $this->paymentMethodRestResponseBuilder = $paymentMethodRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $restCheckoutDataTransfer = $resource->getPayload();
            if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
                continue;
            }

            $restPaymentMethodsResources = $this->paymentMethodRestResponseBuilder->createRestPaymentMethodsResources($restCheckoutDataTransfer);

            foreach ($restPaymentMethodsResources as $restPaymentMethodsResource) {
                $resource->addRelationship($restPaymentMethodsResource);
            }
        }
    }
}

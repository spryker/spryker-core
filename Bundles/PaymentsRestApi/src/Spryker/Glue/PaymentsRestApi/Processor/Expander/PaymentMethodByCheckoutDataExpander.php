<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
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
            $paymentProviderCollectionTransfer = $this->findPaymentProviderCollectionTransfer($resource);

            if (!$paymentProviderCollectionTransfer) {
                continue;
            }

            $restPaymentMethodsResources = $this->paymentMethodRestResponseBuilder
                ->createRestPaymentMethodsResources($paymentProviderCollectionTransfer);

            foreach ($restPaymentMethodsResources as $restPaymentMethodsResource) {
                $resource->addRelationship($restPaymentMethodsResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer|null
     */
    protected function findPaymentProviderCollectionTransfer(RestResourceInterface $restResource): PaymentProviderCollectionTransfer
    {
        $restCheckoutDataTransfer = $restResource->getPayload();

        if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
            return null;
        }

        return $restCheckoutDataTransfer->getPaymentProviders();
    }
}

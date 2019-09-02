<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface;
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface;

class PaymentMethodByCheckoutDataExpander implements PaymentMethodByCheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface
     */
    protected $paymentMethodRestResponseBuilder;

    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface
     */
    protected $paymentMethodMapper;

    /**
     * @param \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder
     * @param \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function __construct(
        PaymentMethodRestResponseBuilderInterface $paymentMethodRestResponseBuilder,
        PaymentMethodMapperInterface $paymentMethodMapper
    ) {
        $this->paymentMethodRestResponseBuilder = $paymentMethodRestResponseBuilder;
        $this->paymentMethodMapper = $paymentMethodMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $restPaymentMethodAttributesTransfers = $this->findPaymentMethodAttributesTransfers($resource);

            if (!$restPaymentMethodAttributesTransfers) {
                continue;
            }

            $this->addPaymentMethodsRelationships($resource, $restPaymentMethodAttributesTransfers);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]|null
     */
    protected function findPaymentMethodAttributesTransfers(RestResourceInterface $restResource): ?array
    {
        $paymentProviderTransfers = $this->findPaymentProviderTransfersInPayload($restResource);

        if (!$paymentProviderTransfers) {
            return null;
        }

        return $this->paymentMethodMapper
            ->mapPaymentProviderTransfersToRestPaymentMethodsAttributesTransfers($paymentProviderTransfers);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer[]|null
     */
    protected function findPaymentProviderTransfersInPayload(RestResourceInterface $restResource): ?array
    {
        $restCheckoutDataTransfer = $this->findPayloadAsRestCheckoutDataTransfer($restResource);

        if (!$restCheckoutDataTransfer) {
            return null;
        }

        $paymentProvidersCollection = $restCheckoutDataTransfer->getPaymentProviders();

        if (!$paymentProvidersCollection) {
            return null;
        }

        return $paymentProvidersCollection->getPaymentProviders()->getArrayCopy();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer|null
     */
    protected function findPayloadAsRestCheckoutDataTransfer(RestResourceInterface $restResource): ?RestCheckoutDataTransfer
    {
        $payload = $restResource->getPayload();

        if (!$payload || !($payload instanceof RestCheckoutDataTransfer)) {
            return null;
        }

        return $payload;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[] $restPaymentMethodAttributesTransfers
     *
     * @return void
     */
    protected function addPaymentMethodsRelationships(
        RestResourceInterface $restResource,
        array $restPaymentMethodAttributesTransfers
    ): void {
        foreach ($restPaymentMethodAttributesTransfers as $idPaymentMethod => $restPaymentMethodAttributesTransfer) {
            $this->addPaymentMethodRelationship($restResource, $idPaymentMethod, $restPaymentMethodAttributesTransfer);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param int $idPaymentMethod
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer $restPaymentMethodAttributesTransfer
     *
     * @return void
     */
    protected function addPaymentMethodRelationship(
        RestResourceInterface $restResource,
        int $idPaymentMethod,
        RestPaymentMethodsAttributesTransfer $restPaymentMethodAttributesTransfer
    ): void {
        $restResource->addRelationship(
            $this->paymentMethodRestResponseBuilder->createRestPaymentMethodResource(
                $idPaymentMethod,
                $restPaymentMethodAttributesTransfer
            )
        );
    }
}

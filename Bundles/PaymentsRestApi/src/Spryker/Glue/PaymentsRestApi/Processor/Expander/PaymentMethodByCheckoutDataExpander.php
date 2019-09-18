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
use Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface;

class PaymentMethodByCheckoutDataExpander implements PaymentMethodByCheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface
     */
    protected $paymentMethodRestResponseBuilder;

    /**
     * @var \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface
     */
    protected $paymentMethodMapper;

    /**
     * @param \Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder\PaymentMethodsRestResponseBuilderInterface $paymentMethodRestResponseBuilder
     * @param \Spryker\Glue\PaymentsRestApi\Processor\Mapper\PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function __construct(
        PaymentMethodsRestResponseBuilderInterface $paymentMethodRestResponseBuilder,
        PaymentMethodMapperInterface $paymentMethodMapper
    ) {
        $this->paymentMethodRestResponseBuilder = $paymentMethodRestResponseBuilder;
        $this->paymentMethodMapper = $paymentMethodMapper;
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
            $restPaymentMethodAttributesTransfers = $this->findPaymentMethodAttributesTransfers($resource);

            if (!$restPaymentMethodAttributesTransfers) {
                continue;
            }

            $this->addPaymentMethodsRelationships($resource, $restPaymentMethodAttributesTransfers);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]|null
     */
    protected function findPaymentMethodAttributesTransfers(RestResourceInterface $restResource): ?array
    {
        $paymentProviderTransfers = $this->findPaymentProviderTransfersInPayload($restResource);

        return $paymentProviderTransfers
            ? $this->paymentMethodMapper->mapPaymentProviderTransfersToRestPaymentMethodsAttributesTransfers($paymentProviderTransfers)
            : null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer[]|null
     */
    protected function findPaymentProviderTransfersInPayload(RestResourceInterface $restResource): ?array
    {
        $restCheckoutDataTransfer = $restResource->getPayload();

        if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
            return null;
        }

        return $restCheckoutDataTransfer->getPaymentProviders()->getPaymentProviders()->getArrayCopy() ?? null;
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
            $this->paymentMethodRestResponseBuilder->createRestPaymentMethodsResource(
                $idPaymentMethod,
                $restPaymentMethodAttributesTransfer
            )
        );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AddressByCheckoutDataResourceRelationshipExpander implements AddressResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface
     */
    protected $addressRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface
     */
    protected $addressResourceMapper;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface $addressRestResponseBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface $addressResourceMapper
     */
    public function __construct(
        AddressRestResponseBuilderInterface $addressRestResponseBuilder,
        AddressResourceMapperInterface $addressResourceMapper
    ) {
        $this->addressRestResponseBuilder = $addressRestResponseBuilder;
        $this->addressResourceMapper = $addressResourceMapper;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $customerTransfer = $this->findCustomerTransferInPayload($resource);
            if (!$customerTransfer) {
                continue;
            }

            $addressesTransfer = $this->findAddressesTransferInPayload($resource);
            if (!$addressesTransfer) {
                continue;
            }

            foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
                $resource->addRelationship(
                    $this->createAddressRestResource($addressTransfer, $customerTransfer)
                );
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer|null
     */
    protected function findAddressesTransferInPayload(RestResourceInterface $restResource): ?AddressesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutDataTransfer|null $payload */
        $payload = $restResource->getPayload();
        if (!$payload || !($payload instanceof RestCheckoutDataTransfer) || !$payload->getAddresses()) {
            return null;
        }

        return $payload->getAddresses();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomerTransferInPayload(RestResourceInterface $restResource): ?CustomerTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutDataTransfer|null $payload */
        $payload = $restResource->getPayload();
        if (!$payload || !($payload instanceof RestCheckoutDataTransfer) || !$payload->getQuote()) {
            return null;
        }

        return $payload->getQuote()->getCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createAddressRestResource(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): RestResourceInterface {
        $restAddressAttributesTransfer = $this->addressResourceMapper
            ->mapAddressTransferToRestAddressAttributesTransfer($addressTransfer, $customerTransfer);

        return $this->addressRestResponseBuilder->createAddressRestResource(
            $addressTransfer->getUuid(),
            $customerTransfer->getCustomerReference(),
            $restAddressAttributesTransfer
        );
    }
}

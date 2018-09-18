<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\CustomersRestApiErrorsTrait;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AddressesReader implements AddressesReaderInterface
{
    use CustomersRestApiErrorsTrait;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface
     */
    protected $addressesResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface $addressesResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressesResourceMapperInterface $addressesResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAddressesByAddressUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $customerReference = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId();

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
        }

        $addressesTransfer = $this->customerClient->getAddresses($customerResponseTransfer->getCustomerTransfer());

        if (!count($addressesTransfer->getAddresses())) {
            return $this->addCustomerAddressesNotFoundError($restResponse);
        }

        if (!$restRequest->getResource()->getId()) {
            $this->getAllAddresses($addressesTransfer, $customerResponseTransfer->getCustomerTransfer(), $restResponse);

            return $restResponse;
        }

        foreach ($addressesTransfer->getAddresses() as $address) {
            if ($address->getUuid() === $restRequest->getResource()->getId()) {
                $addressesResource = $this->addressesResourceMapper->mapAddressTransferToRestResource(
                    $address,
                    $customerResponseTransfer->getCustomerTransfer()
                );

                return $restResponse->addResource($addressesResource);
            }
        }

        return $this->addAddressNotFoundError($restResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getAllAddresses(
        AddressesTransfer $addressesTransfer,
        CustomerTransfer $customerTransfer,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        foreach ($addressesTransfer->getAddresses() as $address) {
            $addressesResource = $this->addressesResourceMapper->mapAddressTransferToRestResource(
                $address,
                $customerTransfer
            );

            $restResponse->addResource($addressesResource);
        }
        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getUser()->getNaturalIdentifier() === $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Address;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AddressWriter implements AddressWriterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface
     */
    protected $addressesResourceMapper;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface
     */
    protected $restApiError;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface
     */
    protected $restApiValidator;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Address\AddressReaderInterface
     */
    protected $addressReader;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Address\AddressReaderInterface $addressReader
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface $addressesResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface $restApiError
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface $restApiValidator
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressReaderInterface $addressReader,
        AddressResourceMapperInterface $addressesResourceMapper,
        RestApiErrorInterface $restApiError,
        RestApiValidatorInterface $restApiValidator
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->addressReader = $addressReader;
        $this->addressesResourceMapper = $addressesResourceMapper;
        $this->restApiError = $restApiError;
        $this->restApiValidator = $restApiValidator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $addressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddress(RestRequestInterface $restRequest, RestAddressAttributesTransfer $addressAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$this->restApiValidator->isSameCustomerReference($restRequest)) {
            return $this->restApiError->addCustomerUnauthorizedError($restResponse);
        }

        $addressTransfer = $this->addressesResourceMapper->mapRestAddressAttributesTransferToAddressTransfer($addressAttributesTransfer);
        $addressTransfer->setFkCustomer((int)$restRequest->getUser()->getSurrogateIdentifier());

        $customerTransfer = $this->customerClient->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer);
        $addressesTransfer = $customerTransfer->getAddresses();

        if (!$addressesTransfer->getAddresses()->count()) {
            return $this->restApiError->addAddressNotSavedError($restResponse);
        }

        $restAddressAttributesTransfer = $this
            ->addressesResourceMapper
            ->mapAddressTransferToRestAddressAttributesTransfer(
                $addressesTransfer->getAddresses()[$addressesTransfer->getAddresses()->count() - 1],
                $customerTransfer
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid(),
            $restAddressAttributesTransfer
        );

        $restResource->addLink(
            RestResourceInterface::RESOURCE_LINKS_SELF,
            $this->createSelfLink($customerTransfer, $addressTransfer)
        );

        $restResponse->addResource($restResource);

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $addressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateAddress(RestRequestInterface $restRequest, RestAddressAttributesTransfer $addressAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->restApiError->addAddressUuidMissingError($restResponse);
        }

        if (!$this->restApiValidator->isSameCustomerReference($restRequest)) {
            return $this->restApiError->addCustomerUnauthorizedError($restResponse);
        }

        $addressTransfer = $this->addressReader->findAddressByUuid($restRequest, $restRequest->getResource()->getId());

        if (!$addressTransfer) {
            return $this->restApiError->addAddressNotFoundError($restResponse);
        }

        $addressTransfer->fromArray($addressAttributesTransfer->modifiedToArray(), true);

        $customerTransfer = $this->customerClient->updateAddressAndCustomerDefaultAddresses($addressTransfer);

        $restAddressAttributesTransfer = $this
            ->addressesResourceMapper
            ->mapAddressTransferToRestAddressAttributesTransfer(
                $this->getAddressByUuid($customerTransfer->getAddresses(), $restRequest->getResource()->getId()),
                $customerTransfer
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid(),
            $restAddressAttributesTransfer
        );

        $restResource->addLink(
            RestResourceInterface::RESOURCE_LINKS_SELF,
            $this->createSelfLink($customerTransfer, $addressTransfer)
        );

        $restResponse->addResource($restResource);

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAddress(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->restApiError->addAddressUuidMissingError($restResponse);
        }

        if (!$this->restApiValidator->isSameCustomerReference($restRequest)) {
            return $this->restApiError->addCustomerUnauthorizedError($restResponse);
        }

        $addressTransfer = $this->addressReader->findAddressByUuid($restRequest, $restRequest->getResource()->getId());

        if (!$addressTransfer) {
            return $this->restApiError->addAddressNotFoundError($restResponse);
        }

        $this->customerClient->deleteAddress($addressTransfer);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressByUuid(AddressesTransfer $addressesTransfer, string $uuid): AddressTransfer
    {
        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            if ($addressTransfer->getUuid() === $uuid) {
                return $addressTransfer;
            }
        }

        return new AddressTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function createSelfLink(CustomerTransfer $customerTransfer, AddressTransfer $addressTransfer): string
    {
        return sprintf(
            CustomersRestApiConfig::FORMAT_SELF_LINK_ADDRESS_RESOURCE,
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerTransfer->getCustomerReference(),
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid()
        );
    }
}

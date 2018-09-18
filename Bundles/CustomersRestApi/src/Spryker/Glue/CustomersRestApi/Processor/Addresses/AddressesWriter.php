<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\CustomersRestApiErrorsTrait;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AddressesWriter implements AddressesWriterInterface
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
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $addressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddress(RestRequestInterface $restRequest, RestAddressAttributesTransfer $addressAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $customerResponseTransfer = $this->findCustomer($restRequest);
        $restResponse = $this->validateCustomerResponseTransfer($customerResponseTransfer, $restRequest, $restResponse);

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        $addressTransfer = $this->addressesResourceMapper->mapRestAddressAttributesTransferToAddressTransfer($addressAttributesTransfer);
        $addressTransfer->setFkCustomer($customerResponseTransfer->getCustomerTransfer()->getIdCustomer());

        $addressTransfer = $this->customerClient->createAddress($addressTransfer);

        if (!$addressTransfer->getUuid()) {
            return $this->addAddressNotSavedError($restResponse);
        }

        $addressesTransfer = $this->customerClient->getAddresses($customerResponseTransfer->getCustomerTransfer());

        $addressTransfer = $this->setCustomersDefaultAddresses(
            $addressTransfer,
            $addressAttributesTransfer,
            $addressesTransfer->getAddresses()->count() === 1
        );

        $this->saveCustomerDefaultAddresses($addressTransfer, $customerResponseTransfer->getCustomerTransfer());

        $restResource = $this
            ->addressesResourceMapper
            ->mapAddressTransferToRestResource(
                $addressTransfer,
                $customerResponseTransfer->getCustomerTransfer()
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
            return $this->addAddressUuidMissingError($restResponse);
        }

        $customerResponseTransfer = $this->findCustomer($restRequest);
        $restResponse = $this->validateCustomerResponseTransfer($customerResponseTransfer, $restRequest, $restResponse);

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        $addressesTransfer = $this->customerClient->getAddresses($customerResponseTransfer->getCustomerTransfer());
        $addressTransfer = $this->findAddressByUuid($addressesTransfer, $restRequest->getResource()->getId());

        if (!$addressTransfer) {
            return $this->addAddressNotFoundError($restResponse);
        }

        $addressTransfer->fromArray($addressAttributesTransfer->modifiedToArray(), true);

        $this->saveCustomerDefaultAddresses($addressTransfer, $customerResponseTransfer->getCustomerTransfer());
        $addressTransfer = $this->customerClient->updateAddress($addressTransfer);

        if (!$addressTransfer->getUuid()) {
            return $this->addAddressNotSavedError($restResponse);
        }

        $restResource = $this
            ->addressesResourceMapper
            ->mapAddressTransferToRestResource(
                $addressTransfer,
                $customerResponseTransfer->getCustomerTransfer()
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

        $customerReference = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId();

        if (!$restRequest->getResource()->getId()) {
            return $this->addAddressUuidMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
        }

        $addressesTransfer = $this->customerClient->getAddresses($customerResponseTransfer->getCustomerTransfer());
        $addressTransfer = $this->findAddressByUuid($addressesTransfer, $restRequest->getResource()->getId());

        if (!$addressTransfer) {
            return $this->addAddressNotFoundError($restResponse);
        }

        $this->customerClient->deleteAddress($addressTransfer);

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getUser()->getNaturalIdentifier()
            === $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function saveCustomerDefaultAddresses(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): void {
        $this->updateCustomerDefaultBillingAddress($addressTransfer, $customerTransfer);
        $this->updateCustomerDefaultShippingAddress($addressTransfer, $customerTransfer);

        if ($customerTransfer->isPropertyModified(CustomerTransfer::DEFAULT_BILLING_ADDRESS)
            || $customerTransfer->isPropertyModified(CustomerTransfer::DEFAULT_SHIPPING_ADDRESS)) {
            $this->customerClient->updateCustomer($customerTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function updateCustomerDefaultBillingAddress(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerTransfer {
        if ($customerTransfer->getDefaultBillingAddress() === $addressTransfer->getIdCustomerAddress()
            && $addressTransfer->getIsDefaultBilling() === false
        ) {
            $customerTransfer->setDefaultBillingAddress(null);
        }

        if ($addressTransfer->getIsDefaultBilling() === true
            && $customerTransfer->getDefaultBillingAddress() !== $addressTransfer->getIdCustomerAddress()
        ) {
            $customerTransfer->setDefaultBillingAddress($addressTransfer->getIdCustomerAddress());
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function updateCustomerDefaultShippingAddress(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerTransfer {
        if ($customerTransfer->getDefaultShippingAddress() === $addressTransfer->getIdCustomerAddress()
            && $addressTransfer->getIsDefaultShipping() === false
        ) {
            $customerTransfer->setDefaultShippingAddress(null);
        }

        if ($addressTransfer->getIsDefaultShipping() === true
            && $customerTransfer->getDefaultShippingAddress() !== $addressTransfer->getIdCustomerAddress()
        ) {
            $customerTransfer->setDefaultShippingAddress($addressTransfer->getIdCustomerAddress());
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function findAddressByUuid(AddressesTransfer $addressesTransfer, string $uuid): ?AddressTransfer
    {
        foreach ($addressesTransfer->getAddresses() as $address) {
            if ($address->getUuid() === $uuid) {
                return $address;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $addressAttributesTransfer
     * @param bool $isFirstAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function setCustomersDefaultAddresses(AddressTransfer $addressTransfer, RestAddressAttributesTransfer $addressAttributesTransfer, bool $isFirstAddress)
    {
        if (!$isFirstAddress) {
            $addressTransfer->setIsDefaultBilling($addressAttributesTransfer->getIsDefaultBilling());
            $addressTransfer->setIsDefaultShipping($addressAttributesTransfer->getIsDefaultShipping());
        } else {
            $addressTransfer->setIsDefaultBilling(true);
            $addressTransfer->setIsDefaultShipping(true);
        }

        return $addressTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function findCustomer(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        $customerReference = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId();
        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);

        return $this->customerClient->findCustomerByReference($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function validateCustomerResponseTransfer(
        CustomerResponseTransfer $customerResponseTransfer,
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
        }

        return $restResponse;
    }
}

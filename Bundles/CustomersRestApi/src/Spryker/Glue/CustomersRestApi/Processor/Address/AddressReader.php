<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AddressReader implements AddressReaderInterface
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
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface
     */
    protected $restApiErrorProcessor;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface
     */
    protected $restApiValidator;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface $addressesResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface $restApiErrorProcessor
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface $restApiValidator
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressResourceMapperInterface $addressesResourceMapper,
        RestApiErrorProcessorInterface $restApiErrorProcessor,
        RestApiValidatorInterface $restApiValidator
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
        $this->restApiErrorProcessor = $restApiErrorProcessor;
        $this->restApiValidator = $restApiValidator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAddressesByAddressUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $parentResource = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS);
        if (!$parentResource) {
            return $this->restApiErrorProcessor->addCustomerReferenceMissingError($restResponse);
        }
        $customerReference = $parentResource->getId();

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        $restResponse = $this->restApiValidator->validateCustomerResponseTransfer(
            $customerResponseTransfer,
            $restRequest,
            $restResponse
        );

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        if (!$restRequest->getResource()->getId()) {
            $this->getAllAddresses($customerResponseTransfer->getCustomerTransfer(), $restResponse);

            return $restResponse;
        }

        return $this->getAddressByCustomerTransfer($customerResponseTransfer->getCustomerTransfer(), $restRequest->getResource()->getId(), $restResponse);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findAddressByUuid(RestRequestInterface $restRequest, string $uuid): ?AddressTransfer
    {
        $customerTransfer = (new CustomerTransfer())->setIdCustomer((int)$restRequest->getUser()->getSurrogateIdentifier());
        $addressesTransfer = $this->customerClient->getAddresses($customerTransfer);

        foreach ($addressesTransfer->getAddresses() as $address) {
            if ($address->getUuid() === $uuid) {
                return $address;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $addressUuid
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getAddressByCustomerTransfer(
        CustomerTransfer $customerTransfer,
        string $addressUuid,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        foreach ($customerTransfer->getAddresses()->getAddresses() as $addressTransfer) {
            if ($addressTransfer->getUuid() == $addressUuid) {
                return $restResponse->addResource(
                    $this->getAddressResource($addressTransfer, $customerTransfer)
                );
            }
        }

        return $this->restApiErrorProcessor->addAddressNotFoundError($restResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getAllAddresses(
        CustomerTransfer $customerTransfer,
        RestResponseInterface $restResponse
    ): RestResponseInterface {
        foreach ($customerTransfer->getAddresses()->getAddresses() as $addressTransfer) {
            $restResponse->addResource($this->getAddressResource($addressTransfer, $customerTransfer));
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getAddressResource(AddressTransfer $addressTransfer, CustomerTransfer $customerTransfer): RestResourceInterface
    {
        $restAddressAttributesTransfer = $this->addressesResourceMapper
            ->mapAddressTransferToRestAddressAttributesTransfer(
                $addressTransfer,
                $customerTransfer
            );

        $restResource = $this->restResourceBuilder
            ->createRestResource(
                CustomersRestApiConfig::RESOURCE_ADDRESSES,
                $addressTransfer->getUuid(),
                $restAddressAttributesTransfer
            )
            ->addLink(
                RestLinkInterface::LINK_SELF,
                $this->createSelfLink($customerTransfer, $addressTransfer)
            );

        return $restResource;
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

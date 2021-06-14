<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class AddressReader implements AddressReaderInterface
{
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
     * @var \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface
     */
    protected $addressRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface $addressesResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorInterface $restApiError
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface $restApiValidator
     * @param \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface $addressRestResponseBuilder
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressResourceMapperInterface $addressesResourceMapper,
        RestApiErrorInterface $restApiError,
        RestApiValidatorInterface $restApiValidator,
        AddressRestResponseBuilderInterface $addressRestResponseBuilder
    ) {
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
        $this->restApiError = $restApiError;
        $this->restApiValidator = $restApiValidator;
        $this->addressRestResponseBuilder = $addressRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAddressesByAddressUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->addressRestResponseBuilder->createRestResponse();
        $parentResource = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS);
        if (!$parentResource) {
            return $this->restApiError->addCustomerReferenceMissingError($restResponse);
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
        $customerTransfer = (new CustomerTransfer())->setIdCustomer((int)$restRequest->getRestUser()->getSurrogateIdentifier());
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

        return $this->restApiError->addAddressNotFoundError($restResponse);
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
    protected function getAddressResource(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): RestResourceInterface {
        $restAddressAttributesTransfer = $this->addressesResourceMapper
            ->mapAddressTransferToRestAddressAttributesTransfer($addressTransfer, $customerTransfer);

        return $this->addressRestResponseBuilder->createAddressRestResource(
            $addressTransfer->getUuid(),
            $customerTransfer->getCustomerReference(),
            $restAddressAttributesTransfer
        );
    }
}

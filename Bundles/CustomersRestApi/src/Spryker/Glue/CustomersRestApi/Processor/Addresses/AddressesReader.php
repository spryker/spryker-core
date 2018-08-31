<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class AddressesReader implements AddressesReaderInterface
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
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface
     */
    protected $addressesResourceMapper;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface
     */
    protected $customerReader;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface $addressesResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface $customersReader
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressesResourceMapperInterface $addressesResourceMapper,
        CustomersReaderInterface $customersReader
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
        $this->customerReader = $customersReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function read(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $customerReference = $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId();

        $customerResponseTransfer = $this
            ->customerReader
            ->findCustomerByReference($customerReference);

        if (!$customerResponseTransfer->getHasCustomer()) {
            $this->createCustomerNotFoundError($restResponse);

            return $restResponse;
        }

        $addressesTransfer = $this->customerClient->getAddresses($customerResponseTransfer->getCustomerTransfer());

        if (!count($addressesTransfer->getAddresses())) {
            $this->createCustomerAddressesNotFoundError($restResponse);

            return $restResponse;
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
                $restResponse->addResource($addressesResource);

                return $restResponse;
            }
        }

        $this->createAddressNotFoundError($restResponse);

        return $restResponse;
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCustomerAddressesNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESSES_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESSES_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createAddressNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_ADDRESS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_ADDRESS_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCustomerNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }
}

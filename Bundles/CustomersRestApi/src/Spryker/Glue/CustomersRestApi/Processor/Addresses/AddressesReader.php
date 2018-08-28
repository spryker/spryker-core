<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
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
    public function read(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->readByCustomerReference(
                $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId()
            );
        }

        return $this->readByUuid(
            $restRequest->getResource()->getId(),
            $restRequest->findParentResourceByType(CustomersRestApiConfig::RESOURCE_CUSTOMERS)->getId()
        );
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByCustomerReference(string $customerReference): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerReference($customerReference);

        $customer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customer) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

            $restResponse->addError($restErrorTransfer);

            return $restResponse;
        }
        /**
         * @var \Generated\Shared\Transfer\AddressesTransfer $addresses
         */
        $addresses = $this->customerClient->getAddresses($customer);

        if (!count($addresses->getAddresses())) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESSES_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESSES_NOT_FOUND);

            $restResponse->addError($restErrorTransfer);

            return $restResponse;
        }

        foreach ($addresses->getAddresses() as $address) {
            $addressesResource = $this->addressesResourceMapper->mapAddressTransferToRestResource(
                $address,
                $customerTransfer->getCustomerReference()
            );

            $restResponse->addResource($addressesResource);
        }

        return $restResponse;
    }

    /**
     * @param string $uuid
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByUuid(string $uuid, string $customerReference): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setUuid($uuid);

        $address = $this->customerClient->findAddressByUuid($addressTransfer);

        if (!$address) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_ADDRESS_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_ADDRESS_NOT_FOUND);

            $restResponse->addError($restErrorTransfer);

            return $restResponse;
        }

        $addressesResource = $this->addressesResourceMapper->mapAddressTransferToRestResource($address, $customerReference);
        $restResponse->addResource($addressesResource);

        return $restResponse;
    }
}

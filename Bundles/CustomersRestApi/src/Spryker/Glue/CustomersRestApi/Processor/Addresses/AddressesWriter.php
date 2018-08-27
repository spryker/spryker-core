<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class AddressesWriter implements AddressesWriterInterface
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
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $addressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddress(RestAddressAttributesTransfer $addressAttributesTransfer): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = $this->addressesResourceMapper->mapRestAddressAttributesTransferToCustomerTransfer($addressAttributesTransfer);
        $customerTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerTransfer) {
            $response->addError($this->createErrorCustomerNotFound());

            return $response;
        }

        $addressTransfer = $this->addressesResourceMapper->mapRestAddressAttributesTransferToAddressTransfer($addressAttributesTransfer);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());

        $addressTransfer = $this->customerClient->createAddress($addressTransfer);

        if (!$addressTransfer->getUuid()) {
            $response->addError($this->createErrorAddressNotSaved());

            return $response;
        }

        $restResource = $this
            ->addressesResourceMapper
            ->mapAddressTransferToRestResource(
                $addressTransfer,
                $addressAttributesTransfer->getCustomerReference()
            );

        $response->addResource($restResource);

        return $response;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteAddress(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $addressTransfer = (new AddressTransfer())->setUuid($restRequest->getResource()->getId());
        $addressTransfer = $this->customerClient->findAddressByUuid($addressTransfer);

        if (!$addressTransfer) {
            $restResponse->addError($this->createErrorAddressNotFound());

            return $restResponse;
        }

        $this->customerClient->deleteAddress($addressTransfer);

        return $restResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorCustomerNotFound(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorAddressNotSaved(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESS_FAILED_TO_SAVE)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESS_FAILED_TO_SAVE);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorAddressNotFound(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_ADDRESS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_ADDRESS_NOT_FOUND);
    }
}

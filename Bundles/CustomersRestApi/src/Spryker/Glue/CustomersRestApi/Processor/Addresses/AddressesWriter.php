<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class AddressesWriter implements AddressesWriterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface $customerClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomerRestApiToCustomerClientInterface $customerClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $addressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAddress(RestAddressAttributesTransfer $addressAttributesTransfer): RestResponseInterface
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setCustomerReference($addressAttributesTransfer->getCustomerReference());

        $customer = $this->customerClient->findCustomerByReference($customerTransfer);

        $response = $this->restResourceBuilder->createRestResponse();

        if (!$customer) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

            $response->addError($restErrorTransfer);

            return $response;
        }
        $addressTransfer = (new AddressTransfer())->fromArray($addressAttributesTransfer->toArray(), true);
        $addressTransfer->setFkCustomer($customer->getIdCustomer());

        $addressTransfer = $this->customerClient->createAddress($addressTransfer);

        if (!$addressTransfer) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESS_FAILED_TO_SAVE)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESS_FAILED_TO_SAVE);

            $response->addError($restErrorTransfer);

            return $response;
        }

        $resource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid()
        );
        $restResourceSelfLink = sprintf(
            '%s/%s/%s/%s',
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $addressAttributesTransfer->getCustomerReference(),
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid()
        );
        $resource->addLink('self', $restResourceSelfLink);

        $response->addResource($resource);

        return $response;
    }
}

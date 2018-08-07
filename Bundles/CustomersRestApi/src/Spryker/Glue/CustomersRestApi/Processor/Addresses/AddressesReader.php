<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class AddressesReader implements AddressesReaderInterface
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
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    protected $addressesResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface $addressesResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomerRestApiToCustomerClientInterface $customerClient,
        AddressesResourceMapperInterface $addressesResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readByIdentifier(string $customerReference): RestResponseInterface
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

        $addressesResource = $this->addressesResourceMapper->mapAddressTransferToRestResource($addresses, $customer);
        $restResponse->addResource($addressesResource);

        return $restResponse;
    }
}

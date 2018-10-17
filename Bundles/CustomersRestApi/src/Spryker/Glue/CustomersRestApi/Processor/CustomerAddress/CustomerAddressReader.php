<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\CustomerAddress;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CustomerAddressReader implements CustomerAddressReaderInterface
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
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface $addressesResourceMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressResourceMapperInterface $addressesResourceMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getAddressesByCustomerReference(RestResourceInterface $restResource): RestResourceInterface
    {
        $customerTransfer = (new CustomerTransfer())->setCustomerReference($restResource->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $restResource;
        }

        $addressesTransfer = $this->customerClient->getAddresses($customerResponseTransfer->getCustomerTransfer());

        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $restAddressAttributesTransfer = $this->addressesResourceMapper
                ->mapAddressTransferToRestAddressAttributesTransfer(
                    $addressTransfer,
                    $customerResponseTransfer->getCustomerTransfer()
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

            $restResource->addRelationship($restResource);
        }

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

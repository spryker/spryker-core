<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class AddressesResourceMapper implements AddressesResourceMapperInterface
{
    protected const RESOURCE_LINKS_SELF = 'self';
    protected const SELF_LINK_FORMAT = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAddressTransferToRestResource(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customerTransfer
    ): RestResourceInterface {
        $restAddressAttributesTransfer = (new RestAddressAttributesTransfer())
            ->fromArray($addressTransfer->toArray(), true)
            ->setCountry($addressTransfer->getCountry()->getName())
            ->setIsDefaultShipping($customerTransfer->getDefaultShippingAddress() === $addressTransfer->getIdCustomerAddress())
            ->setIsDefaultBilling($customerTransfer->getDefaultBillingAddress() === $addressTransfer->getIdCustomerAddress());

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid(),
            $restAddressAttributesTransfer
        );

        $restResource->addLink(static::RESOURCE_LINKS_SELF, $this->createSelfLink($customerTransfer, $addressTransfer));

        return $restResource;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $restAddressAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapRestAddressAttributesTransferToAddressTransfer(
        RestAddressAttributesTransfer $restAddressAttributesTransfer
    ): AddressTransfer {
        return (new AddressTransfer())->fromArray($restAddressAttributesTransfer->toArray(), true);
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
            static::SELF_LINK_FORMAT,
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerTransfer->getCustomerReference(),
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid()
        );
    }
}

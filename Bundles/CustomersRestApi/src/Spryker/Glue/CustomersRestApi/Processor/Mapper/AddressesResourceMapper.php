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
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAddressTransferToRestResource(AddressTransfer $addressTransfer, string $customerReference): RestResourceInterface
    {
        $restAddressAttributesTransfer = (new RestAddressAttributesTransfer())
            ->fromArray($addressTransfer->toArray(), true)
            ->setCountry($addressTransfer->getCountry()->getName());

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid(),
            $restAddressAttributesTransfer
        );
        $restResourceSelfLink = sprintf(
            '%s/%s/%s/%s',
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerReference,
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $addressTransfer->getUuid()
        );
        $restResource->addLink('self', $restResourceSelfLink);

        return $restResource;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressAttributesTransfer $restAddressAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapRestAddressAttributesTransferToCustomerTransfer(
        RestAddressAttributesTransfer $restAddressAttributesTransfer
    ): CustomerTransfer {
        return (new CustomerTransfer())->setCustomerReference($restAddressAttributesTransfer->getCustomerReference());
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
}

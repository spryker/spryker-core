<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Generated\Shared\Transfer\RestAddressesTransfer;
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
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAddressTransferToRestResource(
        AddressesTransfer $addressesTransfer,
        CustomerTransfer $customerTransfer
    ): RestResourceInterface {
        $restAddressesTransfer = new RestAddressesTransfer();

        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $restAddressAttributesTransfer = (new RestAddressAttributesTransfer())
                ->fromArray($addressTransfer->toArray(), true);

            $restAddressAttributesTransfer->setStreet($addressTransfer->getAddress1())
                ->setNumber($addressTransfer->getAddress2())
                ->setAdditionToAddress($addressTransfer->getAddress3())
                ->setCountry($addressTransfer->getCountry()->getName())
                ->setIsDefaultBillingAddress($addressTransfer->getIsDefaultBilling() ? 'Yes' : 'No')
                ->setIsDefaultShippingAddress($addressTransfer->getIsDefaultShipping() ? 'Yes' : 'No');

            $restAddressesTransfer->addAddress($restAddressAttributesTransfer);
        }
        return $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_ADDRESSES,
            $customerTransfer->getCustomerReference(),
            $restAddressesTransfer
        );
    }
}

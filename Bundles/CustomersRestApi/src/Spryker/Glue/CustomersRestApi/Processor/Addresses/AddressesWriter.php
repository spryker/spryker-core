<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Addresses;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RestAddressAttributesTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

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
    public function deleteAddress(RestAddressAttributesTransfer $addressAttributesTransfer): RestResponseInterface
    {
        $addressTransfer = (new AddressTransfer())->fromArray($addressAttributesTransfer->toArray(), true);
        $addressTransfer = $this->customerClient->deleteAddress($addressTransfer);
        $response = $this->restResourceBuilder->createRestResponse();
        return $response;
    }
}

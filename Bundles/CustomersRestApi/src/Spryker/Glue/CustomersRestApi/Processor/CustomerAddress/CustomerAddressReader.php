<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\CustomerAddress;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface;
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
     * @var \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface
     */
    protected $addressRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface $addressesResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\AddressRestResponseBuilderInterface $addressRestResponseBuilder
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        AddressResourceMapperInterface $addressesResourceMapper,
        AddressRestResponseBuilderInterface $addressRestResponseBuilder
    ) {
        $this->customerClient = $customerClient;
        $this->addressesResourceMapper = $addressesResourceMapper;
        $this->addressRestResponseBuilder = $addressRestResponseBuilder;
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

        foreach ($customerResponseTransfer->getCustomerTransfer()->getAddresses()->getAddresses() as $addressTransfer) {
            $restAddressAttributesTransfer = $this->addressesResourceMapper
                ->mapAddressTransferToRestAddressAttributesTransfer(
                    $addressTransfer,
                    $customerResponseTransfer->getCustomerTransfer()
                );

            $restResource->addRelationship(
                $this->addressRestResponseBuilder->createAddressRestResource(
                    $addressTransfer->getUuid(),
                    $customerResponseTransfer->getCustomerTransfer()->getCustomerReference(),
                    $restAddressAttributesTransfer
                )
            );
        }

        return $restResource;
    }
}

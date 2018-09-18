<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\CustomersRestApiErrorsTrait;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomersReader implements CustomersReaderInterface
{
    use CustomersRestApiErrorsTrait;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    protected $customersResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface $customersResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        CustomersResourceMapperInterface $customersResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->customersResourceMapper = $customersResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->addCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($restRequest->getResource()->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
        }

        $customersResource = $this
            ->customersResourceMapper
            ->mapCustomerTransferToRestResource($customerResponseTransfer->getCustomerTransfer());
        $restResponse->addResource($customersResource);

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getUser()->getNaturalIdentifier() === $restRequest->getResource()->getId();
    }
}

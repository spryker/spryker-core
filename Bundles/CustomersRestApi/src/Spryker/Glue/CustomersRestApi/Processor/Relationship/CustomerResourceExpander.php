<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CustomerResourceExpander implements CustomerResourceExpanderInterface
{
    protected const KEY_CUSTOMER = 'customer';

//    /**
//     * @var \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface
//     */
//    protected $companyUsersRestApiClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface $companyUsersRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
      //  CompanyUsersRestApiClientInterface $companyUsersRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        //$this->companyUsersRestApiClient = $companyUsersRestApiClient;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByCustomerReference(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $customerTransfer = $resource->getPayload()->offsetGet(static::KEY_CUSTOMER);
            if (!$customerTransfer) {
                continue;
            }
            $this->addRelationship($resource, $customerTransfer);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return string|null
     */
    protected function findCustomerReference(?AbstractTransfer $attributes): ?string
    {
        if ($attributes
            && $attributes->offsetExists(static::KEY_CUSTOMER)
            && $attributes->offsetGet(static::KEY_CUSTOMER)
        ) {
            return $attributes->offsetGet(static::KEY_CUSTOMER);
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function addRelationship(
        RestResourceInterface $resource,
        CustomerTransfer $customerTransfer
    ): void {
        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerTransfer->getCustomerReference(),
            $customerTransfer
        );

        $resource->addRelationship($restResource);
    }
}

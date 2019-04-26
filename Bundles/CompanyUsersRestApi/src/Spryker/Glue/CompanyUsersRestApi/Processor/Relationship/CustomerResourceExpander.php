<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CustomerResourceExpander implements CustomerResourceExpanderInterface
{
    protected const KEY_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @var \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface
     */
    protected $companyUsersRestApiClient;

    /**
     * @param \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface $companyUsersRestApiClient
     */
    public function __construct(CompanyUsersRestApiClientInterface $companyUsersRestApiClient)
    {
        $this->companyUsersRestApiClient = $companyUsersRestApiClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByCustomerReference(array $resources, RestRequestInterface $restRequest): array
    {
        $customerReferences = [];
        foreach ($resources as $resource) {
            $customerReference = $this->findCustomerReference($resource->getAttributes());
            if (!$customerReference) {
                continue;
            }

            $customerReferences[] = $customerReference;
        }

        $customerReferences = array_unique($customerReferences);

        $customerCollectionTransfer = new CustomerCollectionTransfer();
        foreach ($customerReferences as $customerReference) {
            $customerCollectionTransfer->addCustomer((new CustomerTransfer())->setCustomerReference($customerReference));
        }

        $customerCollectionTransfer = $this->companyUsersRestApiClient
            ->getCustomerCollection($customerCollectionTransfer);

        foreach ($resources as $resource) {
            $this->addRelationship($resource, $customerCollectionTransfer);
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
            && $attributes->offsetExists(static::KEY_CUSTOMER_REFERENCE)
            && $attributes->offsetGet(static::KEY_CUSTOMER_REFERENCE)
        ) {
            return $attributes->offsetGet(static::KEY_CUSTOMER_REFERENCE);
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return void
     */
    protected function addRelationship(
        RestResourceInterface $resource,
        CustomerCollectionTransfer $customerCollectionTransfer
    ): void {
        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            if ($this->findCustomerReference($resource->getAttributes()) === $customerTransfer->getCustomerReference()) {
                $restResource = new RestResource(
                    $customerCollectionTransfer::CUSTOMERS,
                    $customerTransfer->getCustomerReference(),
                    $customerTransfer
                );

                $resource->addRelationship($restResource);
            }
        }
    }
}

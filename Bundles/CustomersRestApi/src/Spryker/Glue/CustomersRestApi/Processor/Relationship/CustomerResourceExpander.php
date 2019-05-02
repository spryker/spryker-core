<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CustomerResourceExpander implements CustomerResourceExpanderInterface
{
    protected const KEY_CUSTOMER = 'customer';

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByCustomerReference(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $customerTransfer = $this->findCustomerTransfer($resource->getPayload());
            if (!$customerTransfer) {
                continue;
            }

            $restResource = $this->restResourceBuilder->createRestResource(
                CustomersRestApiConfig::RESOURCE_CUSTOMERS,
                $customerTransfer->getCustomerReference(),
                $customerTransfer
            );

            $resource->addRelationship($restResource);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $payload
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomerTransfer(?AbstractTransfer $payload): ?CustomerTransfer
    {
        if ($payload
            && $payload->offsetExists(static::KEY_CUSTOMER)
            && $payload->offsetGet(static::KEY_CUSTOMER)
        ) {
            return $payload->offsetGet(static::KEY_CUSTOMER);
        }

        return null;
    }
}

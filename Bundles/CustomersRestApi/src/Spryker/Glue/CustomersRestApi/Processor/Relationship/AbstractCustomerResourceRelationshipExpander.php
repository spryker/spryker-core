<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

abstract class AbstractCustomerResourceRelationshipExpander implements CustomerResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface
     */
    protected $customerRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface
     */
    protected $customerResourceMapper;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder\CustomerRestResponseBuilderInterface $customerRestResponseBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface $customerResourceMapper
     */
    public function __construct(
        CustomerRestResponseBuilderInterface $customerRestResponseBuilder,
        CustomerResourceMapperInterface $customerResourceMapper
    ) {
        $this->customerRestResponseBuilder = $customerRestResponseBuilder;
        $this->customerResourceMapper = $customerResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $customerTransfer = $this->findCustomerTransferInPayload($resource);
            if (!$customerTransfer) {
                continue;
            }

            $restResource = $this->createCustomersRestResourceFromCustomerTransfer($customerTransfer);

            $resource->addRelationship($restResource);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    abstract protected function findCustomerTransferInPayload(RestResourceInterface $restResource): ?CustomerTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCustomersRestResourceFromCustomerTransfer(?CustomerTransfer $customerTransfer): RestResourceInterface
    {
        $restCustomersResponseAttributesTransfer = $this->customerResourceMapper->mapCustomerTransferToRestCustomersResponseAttributesTransfer(
            $customerTransfer,
            new RestCustomersResponseAttributesTransfer()
        );

        return $this->customerRestResponseBuilder->createCustomerRestResource(
            $customerTransfer->getCustomerReference(),
            $restCustomersResponseAttributesTransfer,
            $customerTransfer
        );
    }
}

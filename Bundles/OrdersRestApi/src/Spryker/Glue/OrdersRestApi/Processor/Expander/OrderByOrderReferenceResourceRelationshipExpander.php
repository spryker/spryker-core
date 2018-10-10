<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface;

class OrderByOrderReferenceResourceRelationshipExpander implements OrderByOrderReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface
     */
    protected $orderResource;

    /**
     * @param \Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface $orderResource
     */
    public function __construct(OrdersRestApiResourceInterface $orderResource)
    {
        $this->orderResource = $orderResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $customerReference = $restRequest->getUser()->getNaturalIdentifier();

        if (!$customerReference) {
            return;
        }

        foreach ($resources as $resource) {
            $orderReference = $resource->getId();
            $orderResource = $this->orderResource->findOrderByOrderReference($orderReference, $customerReference);
            if ($orderResource !== null) {
                $resource->addRelationship($orderResource);
            }
        }
    }
}

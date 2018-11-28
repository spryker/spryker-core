<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Processor\Order\OrderReaderInterface;

class OrderByOrderReferenceResourceRelationshipExpander implements OrderByOrderReferenceResourceRelationshipExpanderInterface
{
    protected const ORDER_REFERENCE = 'orderReference';

    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\Order\OrderReaderInterface
     */
    protected $orderReader;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Processor\Order\OrderReaderInterface $orderReader
     */
    public function __construct(OrderReaderInterface $orderReader)
    {
        $this->orderReader = $orderReader;
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
            if (!$resource->getAttributes()->offsetExists(static::ORDER_REFERENCE)) {
                continue;
            }
            $orderReference = $resource->getAttributes()->offsetGet(static::ORDER_REFERENCE);
            $orderResource = $this->orderReader->findCustomerOrder($orderReference, $customerReference);
            if ($orderResource) {
                $resource->addRelationship($orderResource);
            }
        }
    }
}

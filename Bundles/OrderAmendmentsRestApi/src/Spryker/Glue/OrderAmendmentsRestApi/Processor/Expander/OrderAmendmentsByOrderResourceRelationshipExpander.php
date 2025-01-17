<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder\OrderAmendmentsRestResponseBuilderInterface;

class OrderAmendmentsByOrderResourceRelationshipExpander implements OrderAmendmentsByOrderResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder\OrderAmendmentsRestResponseBuilderInterface
     */
    protected OrderAmendmentsRestResponseBuilderInterface $orderAmendmentsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder\OrderAmendmentsRestResponseBuilderInterface $orderAmendmentsRestResponseBuilder
     */
    public function __construct(OrderAmendmentsRestResponseBuilderInterface $orderAmendmentsRestResponseBuilder)
    {
        $this->orderAmendmentsRestResponseBuilder = $orderAmendmentsRestResponseBuilder;
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $orderTransfer = $resource->getPayload();
            if (!$orderTransfer instanceof OrderTransfer || $orderTransfer->getSalesOrderAmendment() === null) {
                continue;
            }

            $resource->addRelationship(
                $this->orderAmendmentsRestResponseBuilder->createOrderAmendmentRestResource($orderTransfer->getSalesOrderAmendment()),
            );
        }
    }
}

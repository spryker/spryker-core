<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnResponseBuilderInterface;

class ReturnItemExpander implements ReturnItemExpanderInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnResponseBuilderInterface
     */
    protected $restReturnResponseBuilder;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnResponseBuilderInterface $restReturnResponseBuilder
     */
    public function __construct(RestReturnResponseBuilderInterface $restReturnResponseBuilder)
    {
        $this->restReturnResponseBuilder = $restReturnResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\ReturnTransfer|null $returnTransfer
             */
            $returnTransfer = $resource->getPayload();

            if (!$returnTransfer instanceof ReturnTransfer) {
                continue;
            }

            $returnItemRestResources = $this->restReturnResponseBuilder
                ->createReturnItemRestResourcesFromReturnTransfer($returnTransfer);

            foreach ($returnItemRestResources as $returnItemRestResource) {
                $resource->addRelationship($returnItemRestResource);
            }
        }
    }
}

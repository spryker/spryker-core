<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource\ProductsResourceRelationToProductAvailabilitiesRestApiInterface;

class AbstractProductsProductAvailabilitiesResourceRelationshipExpander implements AbstractProductsProductAvailabilitiesResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource\ProductsResourceRelationToProductAvailabilitiesRestApiInterface
     */
    protected $productAvailabilitiesResource;

    /**
     * @param \Spryker\Glue\ProductsProductAvailabilitiesResourceRelationship\Dependency\RestResource\ProductsResourceRelationToProductAvailabilitiesRestApiInterface $productAvailabilitiesResource
     */
    public function __construct(ProductsResourceRelationToProductAvailabilitiesRestApiInterface $productAvailabilitiesResource)
    {
        $this->productAvailabilitiesResource = $productAvailabilitiesResource;
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
            $abstractProductsAvailabilityResource = $this->productAvailabilitiesResource
                ->findProductAbstractAvailabilityByAbstractProductId($resource->getId(), $restRequest);
            if ($abstractProductsAvailabilityResource !== null) {
                $resource->addRelationship($abstractProductsAvailabilityResource);
            }
        }
    }
}

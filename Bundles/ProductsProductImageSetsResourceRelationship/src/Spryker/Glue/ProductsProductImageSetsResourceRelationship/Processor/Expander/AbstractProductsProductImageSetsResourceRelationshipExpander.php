<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiResourceInterface;

class AbstractProductsProductImageSetsResourceRelationshipExpander implements AbstractProductsProductImageSetsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiResourceInterface
     */
    protected $productImageSetsRestApiResource;

    /**
     * @param \Spryker\Glue\ProductsProductImageSetsResourceRelationship\Dependency\RestResource\ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiResourceInterface $productImageSetsRestApiResource
     */
    public function __construct(ProductsProductImageSetsResourceRelationshipToProductImageSetsRestApiResourceInterface $productImageSetsRestApiResource)
    {
        $this->productImageSetsRestApiResource = $productImageSetsRestApiResource;
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
            $abstractProductImageSetsResource = $this->productImageSetsRestApiResource
                ->findAbstractProductImageSetsBySku($resource->getId(), $restRequest);
            if ($abstractProductImageSetsResource !== null) {
                $resource->addRelationship($abstractProductImageSetsResource);
            }
        }
    }
}

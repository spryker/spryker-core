<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface;

class AbstractProductsCategoriesResourceRelationshipExpander implements AbstractProductsCategoriesResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface
     */
    protected $categoriesResource;

    /**
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource\ProductsCategoriesResourceRelationToCategoriesRestApiInterface $categoriesResource
     */
    public function __construct(ProductsCategoriesResourceRelationToCategoriesRestApiInterface $categoriesResource)
    {
        $this->categoriesResource = $categoriesResource;
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
            $abstractCategoriesResource = $this->categoriesResource
                ->getProductCategoriesResourceBySku($restRequest);
            $resource->addRelationship($abstractCategoriesResource);
        }
    }
}

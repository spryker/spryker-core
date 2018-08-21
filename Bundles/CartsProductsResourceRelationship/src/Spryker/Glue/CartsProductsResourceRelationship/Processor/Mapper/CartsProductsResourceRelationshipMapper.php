<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsProductsResourceRelationship\Processor\Mapper;

use Spryker\Glue\CartsProductsResourceRelationship\Dependency\RestResource\CartsProductsResourceRelationToProductsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartsProductsResourceRelationshipMapper implements CartsProductsResourceRelationshipMapperInterface
{
    /**
     * @var \Spryker\Glue\CartsProductsResourceRelationship\Dependency\RestResource\CartsProductsResourceRelationToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * @param \Spryker\Glue\CartsProductsResourceRelationship\Dependency\RestResource\CartsProductsResourceRelationToProductsRestApiInterface $productsResource
     */
    public function __construct(CartsProductsResourceRelationToProductsRestApiInterface $productsResource)
    {
        $this->productsResource = $productsResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function mapResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $productResource = $this->productsResource->findByConcreteProductSku($resource->getId(), $restRequest);
            if ($productResource !== null) {
                $resource->addRelationship($productResource);
            }
        }
    }
}

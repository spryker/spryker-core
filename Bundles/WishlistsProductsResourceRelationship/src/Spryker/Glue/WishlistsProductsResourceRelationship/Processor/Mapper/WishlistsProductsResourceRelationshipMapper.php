<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsProductsResourceRelationship\Processor\Mapper;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsProductsResourceRelationship\Dependency\RestResource\WishlistsProductsResourceRelationshipToProductsRestApiInterface;

class WishlistsProductsResourceRelationshipMapper implements WishlistsProductsResourceRelationshipMapperInterface
{
    /**
     * @var \Spryker\Glue\WishlistsProductsResourceRelationship\Dependency\RestResource\WishlistsProductsResourceRelationshipToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * WishlistsProductsResourceRelationshipMapper constructor.
     *
     * @param \Spryker\Glue\WishlistsProductsResourceRelationship\Dependency\RestResource\WishlistsProductsResourceRelationshipToProductsRestApiInterface $productsResource
     */
    public function __construct(WishlistsProductsResourceRelationshipToProductsRestApiInterface $productsResource)
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
            $concreteProduct = $this->productsResource->findOneByProductConcreteSku($resource->getAttributes()['sku'], $restRequest);
            if ($concreteProduct !== null) {
                $resource->addRelationship($concreteProduct);
            }
        }
    }
}
